<?php

namespace Tests\Integration;

use App\Models\EventsModel;
use App\Models\ORM\Event;
use App\Repositories\Event\EventRepository;
use Helpers\Constants\EventSteps;

class EventsModelTest extends DatabaseTestCase
{
    use Fixtures;

    private EventsModel $model;
    private int $translatorID;
    private int $eventID;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new EventsModel(new EventRepository(new Event()));

        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('gen', 'Genesis', 50, 1);
        $this->book('exo', 'Exodus', 40, 2);

        // A ULB Genesis event into Indonesian with one translator
        $this->translatorID = $this->member(['firstName' => 'John', 'lastName' => 'Smith']);
        $this->eventID = $this->event($this->project(['targetLang' => 'id']));
    }

    // --- Chapters

    public function testGetChaptersOfEventInOrderAsArrays(): void
    {
        $trID = $this->translator($this->translatorID, $this->eventID);
        $this->chapter($trID, 3);
        $this->chapter($trID, 1);
        $this->chapter($this->translator($this->member(), $this->eventID), 2);
        $this->chapter($this->translator($this->translatorID, $this->event($this->project())), 1);

        $chapters = $this->model->getChapters($this->eventID, null, null, null);

        $this->assertSame([1, 2, 3], array_column($chapters, 'chapter'));
        $this->assertIsArray($chapters[0]);
    }

    public function testGetChaptersFiltersByMemberAndChapter(): void
    {
        $trID = $this->translator($this->translatorID, $this->eventID);
        $this->chapter($trID, 1);
        $this->chapter($trID, 2);
        $this->chapter($this->translator($this->member(), $this->eventID), 3);

        $this->assertSame([1, 2], array_column($this->model->getChapters($this->eventID, $this->translatorID), 'chapter'));
        $this->assertSame([2], array_column($this->model->getChapters($this->eventID, $this->translatorID, 2), 'chapter'));
        $this->assertSame([], $this->model->getChapters($this->eventID, $this->translatorID, 3));
    }

    public function testGetChaptersJoinsTheTranslator(): void
    {
        $trID = $this->translator($this->translatorID, $this->eventID, ['step' => EventSteps::MULTI_DRAFT]);
        $this->chapter($trID, 1);

        $chapter = $this->model->getChapters($this->eventID, $this->translatorID)[0];

        $this->assertSame(EventSteps::MULTI_DRAFT, $chapter['step']);
    }

    public function testGetChaptersRestoresTheSharedFetchMode(): void
    {
        $this->model->getChapters($this->eventID);

        $this->assertIsObject(self::db()->selectOne('SELECT 1 AS one'));
    }

    public function testGetNextChapterPerLevel(): void
    {
        $other = $this->member();
        $trID = $this->translator($this->translatorID, $this->eventID);
        $this->chapter($trID, 1, ['done' => 1, 'l2memberID' => $other, 'l2checked' => 1]);
        $this->chapter($trID, 4, ['l2memberID' => $other]);
        $this->chapter($trID, 2, ['l3memberID' => $other]);
        $this->chapter($this->translator($other, $this->eventID), 3);

        $chapters = fn($level) => array_map(fn($c) => $c->chapter, $this->model->getNextChapter($this->eventID, $level === 'l1' ? $this->translatorID : $other, $level));

        $this->assertSame([2, 4], $chapters('l1'), 'not done, in chapter order');
        $this->assertSame([4], $chapters('l2'), 'not L2 checked');
        $this->assertSame([2], $chapters('l3'));
    }

    public function testAssignUpdateAndRemoveChapter(): void
    {
        $trID = $this->translator($this->translatorID, $this->eventID);
        $chapterID = $this->model->assignChapter([
            'eventID' => $this->eventID, 'memberID' => $this->translatorID, 'trID' => $trID, 'chapter' => 7, 'chunks' => '',
        ]);

        $this->assertSame(1, $this->model->updateChapter(['done' => 1], ['chapterID' => $chapterID]));
        $this->assertSame(1, (int)self::db()->table('chapters')->where('chapterID', $chapterID)->first()->done);
        $this->assertSame(1, $this->model->removeChapter(['chapterID' => $chapterID]));
        $this->assertSame(0, self::db()->table('chapters')->count());
    }

    public function testUpdateTranslatorOnlyTouchesMatchingRows(): void
    {
        $trID = $this->translator($this->translatorID, $this->eventID);
        $otherID = $this->translator($this->member(), $this->eventID);

        $this->model->updateTranslator(['step' => EventSteps::CONSUME, 'currentChapter' => 2], ['trID' => $trID]);

        $step = fn($id) => self::db()->table('translators')->where('trID', $id)->first()->step;
        $this->assertSame(EventSteps::CONSUME, $step($trID));
        $this->assertSame(EventSteps::PRAY, $step($otherID));
    }

    // --- Events of a member

    public function testMemberEventWithProjectLanguageAndBook(): void
    {
        $admin = $this->member();
        $this->eventAdmin($admin, $this->eventID);
        $this->translator($this->translatorID, $this->eventID, ['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 1]);
        $this->translator($this->member(), $this->eventID);
        $this->translator($this->member(), $this->event($this->project()));

        $events = $this->model->getMemberEvents($this->translatorID);

        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertSame([$this->eventID, $this->translatorID, 'Indonesian', 'Genesis', 50, 2, 0],
            [$event->eventID, $event->myMemberID, $event->tLang, $event->name, $event->chaptersNum, $event->currTrs, $event->checkerID]);
        $this->assertSame([$admin], $event->admins->map(fn($m) => $m->memberID)->toArray());
    }

    public function testEachMemberEventHasItsOwnAdmins(): void
    {
        $exodusID = $this->event($this->project(['targetLang' => 'id']), ['bookCode' => 'exo']);
        $genesisAdmin = $this->member();
        $exodusAdmin = $this->member();
        $this->eventAdmin($genesisAdmin, $this->eventID);
        $this->eventAdmin($exodusAdmin, $exodusID);
        $this->translator($this->translatorID, $this->eventID, ['step' => EventSteps::MULTI_DRAFT]);
        $this->translator($this->translatorID, $exodusID, ['step' => EventSteps::MULTI_DRAFT]);

        $admins = [];
        foreach ($this->model->getMemberEvents($this->translatorID) as $event) {
            $admins[$event->name] = $event->admins->map(fn($m) => $m->memberID)->toArray();
        }

        $this->assertSame(['Genesis' => [$genesisAdmin], 'Exodus' => [$exodusAdmin]], $admins);
    }

    public function testPrayingTranslatorGetsTheirNextUnfinishedChapter(): void
    {
        $trID = $this->translator($this->translatorID, $this->eventID, ['step' => EventSteps::PRAY, 'currentChapter' => 0]);
        $this->chapter($trID, 1, ['done' => 1]);
        $this->chapter($trID, 5);

        $this->assertSame(5, $this->model->getMemberEvents($this->translatorID)[0]->currentChapter);
    }

    public function testPrayingWordsTranslatorGetsTheWordsOfTheirNextGroup(): void
    {
        $eventID = $this->event($this->project(['bookProject' => 'tw']), ['bookCode' => 'wkt']);
        $groupID = $this->wordGroup($eventID, ['god', 'love']);
        $this->chapter($this->translator($this->translatorID, $eventID, ['step' => EventSteps::PRAY]), $groupID);

        $event = $this->model->getMemberEvents($this->translatorID, $eventID)[0];

        $this->assertSame($groupID, $event->currentChapter);
        $this->assertSame('["god","love"]', $event->words);
    }

    public function testScriptureTranslatorInCheckingIsListedOncePerOpenCheckOnlyWithCheckers(): void
    {
        $checker = $this->member(['firstName' => 'Mary', 'lastName' => 'Jones']);
        $trID = $this->translator($this->translatorID, $this->eventID, [
            'step' => EventSteps::PEER_REVIEW,
            'peerCheck' => [1 => ['memberID' => $checker, 'done' => 1], 2 => ['memberID' => 0, 'done' => 0], 3 => ['memberID' => $checker, 'done' => 2]],
            'kwCheck' => [1 => ['memberID' => 0, 'done' => 0]],
        ]);
        $this->chapter($trID, 1, ['chunks' => '[[1,2],[3]]']);
        $this->chapter($trID, 2, ['chunks' => '[[1]]']);

        $this->assertSame([], $this->model->getMemberEvents($this->translatorID), 'checking steps are hidden without checkers');

        $events = $this->model->getMemberEvents($this->translatorID, null, null, true);

        $this->assertSame(
            [[EventSteps::PEER_REVIEW, 1, $checker, true], [EventSteps::PEER_REVIEW, 2, 0, false], [EventSteps::KEYWORD_CHECK, 1, 0, false]],
            array_map(fn($e) => [$e->step, $e->currentChapter, $e->checkerID, $e->checkDone], $events),
            'finished checks (done 2) are left out'
        );
        $this->assertSame([['name' => 'Mary J.', 'id' => $checker]], $events[0]->checkers);
        $this->assertSame('[[1,2],[3]]', $events[0]->chunks);
        $this->assertSame('[[1]]', $events[1]->chunks);

        $this->assertSame([2], array_map(
            fn($e) => $e->currentChapter,
            $this->model->getMemberEvents($this->translatorID, null, 2, true)
        ), 'chapter filter');
    }

    public function testHelpToolEventsCanExcludeFinishedAndUnstartedTranslators(): void
    {
        $notesID = $this->event($this->project(['bookProject' => 'tn']));
        $finishedID = $this->event($this->project(['bookProject' => 'tn']), ['bookCode' => 'exo']);
        $this->translator($this->translatorID, $notesID, ['step' => EventSteps::NONE]);
        $this->translator($this->translatorID, $finishedID, ['step' => EventSteps::FINISHED]);

        $this->assertCount(2, $this->model->getMemberEvents($this->translatorID));
        $this->assertSame([$notesID], array_map(fn($e) => $e->eventID,
            $this->model->getMemberEvents($this->translatorID, null, null, false, false)));
        $this->assertSame([$finishedID], array_map(fn($e) => $e->eventID,
            $this->model->getMemberEvents($this->translatorID, null, null, false, true, false)));
    }
}
