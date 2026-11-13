<?php

namespace Tests\Http;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * The AJAX endpoints of the translation pages (events/rpc/...): autosave, comments, notifications.
 * One Jude translation event into Indonesian with a translator, and an outsider who is not in the event.
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class AjaxTest extends HttpTestCase
{
    private int $translator;
    private int $outsider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('jud', 'Jude', 1, 65);
        $this->book('obs', 'Open Bible Stories', 1, 101);

        foreach (['translator' => 'Tom', 'outsider' => 'Olga'] as $role => $name) {
            $this->$role = $this->member(['firstName' => $name, 'lastName' => 'Smith']);
            $this->profile($this->$role, [], ['complete' => 1]);
        }
    }

    /** An event of $bookProject whose translator is at $step of chapter 1, split into chunks [1] and [2]. */
    private function eventAt(string $step, string $bookProject = 'ulb', string $bookCode = 'jud'): array
    {
        $eventID = $this->event($this->project(['bookProject' => $bookProject, 'targetLang' => 'id']), ['bookCode' => $bookCode]);
        $trID = $this->translator($this->translator, $eventID, ['step' => $step, 'currentChapter' => 1]);
        $this->chapter($trID, 1, ['chunks' => '[[1],[2]]']);
        return [$eventID, $trID];
    }

    private function rpc(string $endpoint, array $data): array
    {
        return $this->json($this->post("/events/rpc/$endpoint", $data, true));
    }

    /** Translated verses of chapter 1, by chunk. */
    private function translations(int $eventID): array
    {
        return array_map(fn($t) => json_decode($t->translatedVerses, true),
            self::db()->table('translations')->where('eventID', $eventID)->where('chapter', 1)->orderBy('chunk')->get());
    }

    // --- Autosave

    public function testTranslatorAutosavesTheirDraft(): void
    {
        [$eventID, $trID] = $this->eventAt('multi-draft');
        foreach ([0, 1] as $chunk) {
            $this->translation($trID, 1, $chunk, [], ['translatedVerses' => json_encode(['translator' => ['blind' => 'Old']])]);
        }
        $this->loginAs($this->translator);

        $this->assertSame(['success' => true],
            $this->rpc('autosave_chunk', ['eventID' => $eventID, 'chunks' => [1 => 'New text']]));

        $this->assertSame(['Old', 'New text'], array_map(fn($t) => $t['translator']['blind'], $this->translations($eventID)));
    }

    public function testUnchangedDraftIsNotSaved(): void
    {
        [$eventID, $trID] = $this->eventAt('multi-draft');
        $this->translation($trID, 1, 0, [], ['translatedVerses' => json_encode(['translator' => ['blind' => 'Same']])]);
        $this->loginAs($this->translator);

        $this->assertSame(['success' => false, 'errorType' => 'noChange', 'error' => 'no_change'],
            $this->rpc('autosave_chunk', ['eventID' => $eventID, 'chunks' => ['Same']]));
    }

    public function testFirstAutosaveOfAResourceCreatesItsChunks(): void
    {
        // OBS starts with multi-draft, so the first autosave of a chapter has no translation rows yet
        [$eventID] = $this->eventAt('multi-draft', 'obs', 'obs');
        $this->loginAs($this->translator);

        $this->assertSame(['success' => true],
            $this->rpc('autosave_chunk', ['eventID' => $eventID, 'chunks' => ['Story', 'Picture']]));

        $this->assertSame(['Story', 'Picture'], array_map(fn($t) => $t['translator']['verses'], $this->translations($eventID)));
    }

    public function testOutsidersCannotAutosave(): void
    {
        [$eventID, $trID] = $this->eventAt('multi-draft');
        $this->translation($trID, 1, 0, [], ['translatedVerses' => json_encode(['translator' => ['blind' => 'Old']])]);
        $this->loginAs($this->outsider);

        $this->assertSame(['success' => false], $this->rpc('autosave_chunk', ['eventID' => $eventID, 'chunks' => ['Hacked']]));
        $this->assertSame('Old', $this->translations($eventID)[0]['translator']['blind']);
    }

    public function testTranslatorCannotChangeAChapterWhosePeerCheckIsDone(): void
    {
        $peer = $this->member();
        [$eventID, $trID] = $this->eventAt('peer-review');
        self::db()->table('translators')->where('trID', $trID)
            ->update(['peerCheck' => json_encode([1 => ['memberID' => $peer, 'done' => 1]])]);
        $this->translation($trID, 1, 0, [], ['translatedVerses' => json_encode(['translator' => ['blind' => 'Checked']])]);
        $this->loginAs($this->translator);

        $answer = $this->rpc('autosave_chunk', ['eventID' => $eventID, 'isChecking' => true, 'checkingChapter' => 1,
            'chunks' => ['Changed']]);

        $this->assertSame([false, 'checkDone'], [$answer['success'], $answer['errorType']]);
        $this->assertSame('Checked', $this->translations($eventID)[0]['translator']['blind']);
    }

    public function testSignedOutRequestsAreToldToSignIn(): void
    {
        foreach (['/events/rpc/autosave_chunk', '/events/information/1', '/events/rpc/assign_chapter'] as $endpoint) {
            $answer = $this->json($this->post($endpoint, [], true));
            $this->assertSame('logout', $answer['errorType'], $endpoint);
        }
    }

    // --- Comments

    private function comment(int $eventID, string $text, bool $autoSaver = false): array
    {
        return $this->rpc('save_comment', ['eventID' => $eventID, 'chapter' => 1, 'chunk' => 0, 'comment' => $text,
            'level' => 1, 'autoSaver' => $autoSaver ? 'true' : 'false']);
    }

    public function testTranslatorSavesAndEditsTheirComment(): void
    {
        [$eventID] = $this->eventAt('multi-draft');
        $this->loginAs($this->translator);

        $first = $this->comment($eventID, 'Check this', true);
        $this->assertTrue($first['success']);
        $this->assertSame('Tom S', $first['user']);

        $second = $this->comment($eventID, 'Check this verse');
        $this->assertSame($first['cID'], $second['cID'], 'One comment per member and chunk');

        $comment = self::db()->table('comments')->where('cID', $first['cID'])->first();
        $this->assertSame(['Check this verse', 1], [$comment->text, (int)$comment->saved]);
    }

    public function testOutsidersCannotComment(): void
    {
        [$eventID] = $this->eventAt('multi-draft');
        $this->loginAs($this->outsider);

        $this->assertSame(['success' => false], $this->comment($eventID, 'Spam'));
        $this->assertSame(0, self::db()->table('comments')->count());
    }

    public function testMembersDeleteOnlyTheirOwnComments(): void
    {
        [$eventID] = $this->eventAt('multi-draft');
        $cID = $this->insert('comments', ['eventID' => $eventID, 'chapter' => 1, 'chunk' => 0,
            'memberID' => $this->translator, 'text' => 'Mine']);

        $this->loginAs($this->outsider);
        $this->assertSame(['success' => false], $this->rpc('delete_comment', ['cID' => $cID]));

        $this->loginAs($this->translator);
        $this->assertSame(['success' => true], $this->rpc('delete_comment', ['cID' => $cID]));
        $this->assertSame(0, self::db()->table('comments')->count());
    }

    // --- Other

    public function testNotificationsAnswer(): void
    {
        $this->loginAs($this->outsider);

        $answer = $this->rpc('get_notifications', []);
        $this->assertTrue($answer['success']);
        $this->assertSame([], $answer['notifications']);
    }

    public function testCheckEventTellsTranslatorsWhenTheEventHasStarted(): void
    {
        [$eventID] = $this->eventAt('multi-draft');

        $this->loginAs($this->translator);
        $this->assertSame(['success' => true], $this->rpc('check_event', ['eventID' => $eventID]));

        $this->loginAs($this->outsider);
        $this->assertSame(['success' => false], $this->rpc('check_event', ['eventID' => $eventID]));
    }
}
