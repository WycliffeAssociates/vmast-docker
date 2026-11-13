<?php

namespace Tests\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Event information pages end to end: a finished one-chapter event of each project type, viewed by its event admin.
 * The AJAX form of each page returns the overall progress computed by that type's progress class.
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class InformationTest extends HttpTestCase
{
    private int $admin;
    private int $translator;
    private int $checker;
    private int $peer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('jud', 'Jude', 1, 65);
        $this->book('obs', 'Open Bible Stories', 1, 101);

        $this->admin = $this->member(['firstName' => 'Anna', 'lastName' => 'Admin']);
        $this->profile($this->admin, [], ['complete' => 1]);
        $this->translator = $this->member(['firstName' => 'Tom', 'lastName' => 'Translator']);
        $this->checker = $this->member(['firstName' => 'Carl', 'lastName' => 'Checker']);
        $this->peer = $this->member(['firstName' => 'Mary', 'lastName' => 'Jones']);
    }

    /** An event of $bookProject administered by $this->admin. */
    private function adminEvent(string $bookProject, string $bookCode = 'jud', array $project = []): int
    {
        $eventID = $this->event($this->project(['bookProject' => $bookProject, 'targetLang' => 'id'] + $project), ['bookCode' => $bookCode]);
        $this->eventAdmin($this->admin, $eventID);
        return $eventID;
    }

    /** The translator's chapter $chapter, with $checks, translated in one chunk. */
    private function translatedChapter(int $eventID, array $checks, int $chapter = 1, array $translation = [], array $chapterRow = []): void
    {
        $trID = $this->translator($this->translator, $eventID, ['step' => 'finished', 'currentChapter' => 0] + $checks);
        $this->chapter($trID, $chapter, $chapterRow + ['chunks' => '[[1]]', 'done' => 1]);
        $this->translation($trID, $chapter, 0, ['1' => 'Text'], $translation);
    }

    private function done(int $memberID, int $done, array $more = []): array
    {
        return [1 => ['memberID' => $memberID, 'done' => $done] + $more];
    }

    // --- One finished event per project type: [route, overall progress]

    private function scriptureEvent(): array
    {
        $eventID = $this->adminEvent('ulb');
        $this->translatedChapter($eventID, [
            'verbCheck' => $this->done($this->peer, 1),
            'peerCheck' => $this->done($this->peer, 2),
            'kwCheck' => $this->done($this->checker, 2),
            'crCheck' => $this->done($this->checker, 2, ['memberID2' => $this->peer, 'done2' => 1]),
            'otherCheck' => $this->done(0, 1),
        ]);
        return ["/events/information/$eventID", 100];
    }

    private function notesEvent(): array
    {
        $eventID = $this->adminEvent('tn');
        $this->translatedChapter($eventID, ['otherCheck' => $this->done($this->checker, 6), 'peerCheck' => $this->done($this->peer, 1)], 1,
            ['translatedVerses' => json_encode(['translator' => ['verses' => 'Note'], 'checker' => ['verses' => 'Checked note']])]);
        // The book intro is chapter 0 and is not assigned
        return ["/events/information-tn/$eventID", 50];
    }

    private function questionsEvent(): array
    {
        $eventID = $this->adminEvent('tq');
        $this->translatedChapter($eventID, ['otherCheck' => $this->done($this->checker, 3), 'peerCheck' => $this->done($this->peer, 1)]);
        return ["/events/information-tq/$eventID", 100];
    }

    private function wordsEvent(): array
    {
        $eventID = $this->adminEvent('tw', 'wkt');
        $groupID = $this->wordGroup($eventID, ['god', 'love']);
        $this->translatedChapter($eventID, ['otherCheck' => [$groupID => ['memberID' => $this->checker, 'done' => 3]],
            'peerCheck' => [$groupID => ['memberID' => $this->peer, 'done' => 1]]], $groupID);
        return ["/events/information-tw/$eventID", 100];
    }

    private function sunEvent(): array
    {
        $eventID = $this->adminEvent('sun');
        $this->translatedChapter($eventID, ['kwCheck' => $this->done($this->checker, 1), 'crCheck' => $this->done($this->peer, 2)]);
        return ["/events/information-sun/$eventID", 100];
    }

    private function odbSunEvent(): array
    {
        $eventID = $this->adminEvent('sun', 'jud', ['sourceBible' => 'odb']);
        // The page shows the ODB source's chapter titles; it is normally downloaded and cached
        $this->app['cache']->put('en_odb_jud', json_encode(['chapters' => [1 => [1 => 'Devotion title']]]), 60);
        $this->translatedChapter($eventID, ['kwCheck' => $this->done($this->checker, 1), 'crCheck' => $this->done($this->peer, 1)]);
        return ["/events/information-odb-sun/$eventID", 100];
    }

    private function radioEvent(): array
    {
        $eventID = $this->adminEvent('rad');
        $this->translatedChapter($eventID, ['peerCheck' => $this->done($this->peer, 1)]);
        return ["/events/information-rad/$eventID", 100];
    }

    private function obsEvent(): array
    {
        $eventID = $this->adminEvent('obs', 'obs');
        $this->translatedChapter($eventID, ['otherCheck' => $this->done($this->checker, 3), 'peerCheck' => $this->done($this->peer, 1)]);
        return ["/events/information-obs/$eventID", 100];
    }

    private function bcEvent(): array
    {
        $eventID = $this->adminEvent('bc');
        $this->translatedChapter($eventID, ['otherCheck' => $this->done($this->checker, 3), 'peerCheck' => $this->done($this->peer, 1)]);
        // BC has a book intro as chapter 0
        return ["/events/information-bc/$eventID", 50];
    }

    private function revisionEvent(): array
    {
        $eventID = $this->adminEvent('ulb');
        $l2chID = $this->checkerL2($this->checker, $eventID, ['peerCheck' => $this->done($this->peer, 2),
            'kwCheck' => $this->done($this->peer, 2), 'crCheck' => $this->done($this->peer, 2)]);
        $this->translatedChapter($eventID, [], 1, [], ['l2memberID' => $this->checker, 'l2chID' => $l2chID]);
        return ["/events/information-revision/$eventID", 100];
    }

    private function sunRevisionEvent(): array
    {
        $eventID = $this->adminEvent('sun');
        $l2chID = $this->checkerL2($this->checker, $eventID, ['peerCheck' => $this->done($this->peer, 1)]);
        $this->translatedChapter($eventID, [], 1, [], ['l2memberID' => $this->checker, 'l2chID' => $l2chID]);
        return ["/events/information-sun-revision/$eventID", 100];
    }

    private function reviewEvent(): array
    {
        $eventID = $this->adminEvent('ulb');
        $l3chID = $this->checkerL3($this->checker, $eventID, ['step' => 'finished', 'peerCheck' => $this->done($this->peer, 2)]);
        $this->translatedChapter($eventID, [], 1, [], ['l3memberID' => $this->checker, 'l3chID' => $l3chID]);
        return ["/events/information-review/$eventID", 100];
    }

    public static function finishedEvents(): array
    {
        return array_combine($names = ['scripture', 'notes', 'questions', 'words', 'sun', 'odbSun', 'radio', 'obs', 'bc',
            'revision', 'sunRevision', 'review'], array_map(fn($n) => [$n], $names));
    }

    #[DataProvider('finishedEvents')]
    public function testFinishedEventProgress(string $scenario): void
    {
        [$page, $progress] = $this->{$scenario . 'Event'}();
        $this->loginAs($this->admin);

        $info = $this->json($this->get($page, true));

        $this->assertTrue($info['success'], $page);
        $this->assertEqualsWithDelta($progress, $info['progress'], 0.01, $page);
        $this->assertSame([$this->admin], $info['admins']);
    }

    public function testFinishedBcaEventIsComplete(): void
    {
        // BCA chapters are words, numbered by their word ID (as chapter assignment and the page itself do)
        $eventID = $this->adminEvent('bca');
        $trID = $this->translator($this->translator, $eventID, ['step' => 'finished', 'currentChapter' => 0,
            'otherCheck' => [1001 => ['memberID' => $this->checker, 'done' => 3], 1002 => ['memberID' => $this->checker, 'done' => 3]],
            'peerCheck' => [1001 => ['memberID' => $this->peer, 'done' => 1], 1002 => ['memberID' => $this->peer, 'done' => 1]]]);
        foreach ([1001 => 'Abraham', 1002 => 'Moses'] as $wordID => $word) {
            $this->insert('words', ['wordID' => $wordID, 'eventID' => $eventID, 'word' => $word]);
            $this->chapter($trID, $wordID, ['chunks' => '[[1]]', 'done' => 1]);
            $this->translation($trID, $wordID, 0, ['1' => 'Text']);
        }
        $this->loginAs($this->admin);

        $this->assertEqualsWithDelta(100, $this->json($this->get("/events/information-bca/$eventID", true))['progress'], 0.01);
    }

    // --- The full page

    public function testScriptureInformationPageListsTheTeam(): void
    {
        [$page] = $this->scriptureEvent();
        $this->loginAs($this->admin);

        $info = $this->json($this->get($page, true));
        $this->assertSame(['Anna A.', 'Tom T.', 'Carl C.', 'Mary J.'], array_values(array_filter(array_map(
            fn($m) => is_array($m) ? $m['name'] : null, [$info['members'][$this->admin], $info['members'][$this->translator],
                $info['members'][$this->checker], $info['members'][$this->peer]]))));

        $html = $this->get($page);
        $this->assertStatus(200, $html);
        $this->assertSee('Jude', $html);
        $this->assertSee('Tom T.', $html);
    }

    public function testOtherProjectTypesAreSentToTheEventList(): void
    {
        $notes = $this->adminEvent('tn');
        $this->loginAs($this->admin);

        $this->assertRedirectedTo('events', $this->get("/events/information/$notes"));
        $this->assertRedirectedTo('events', $this->get('/events/information-tq/' . $this->adminEvent('ulb')));
    }

    public function testOutsidersGetANoPermissionAnswer(): void
    {
        [$page] = $this->scriptureEvent();
        $outsider = $this->member();
        $this->profile($outsider, [], ['complete' => 1]);
        $this->loginAs($outsider);

        $this->assertSame(['success' => false, 'errorType' => 'empty_no_permission'], $this->json($this->get($page, true)));
    }
}
