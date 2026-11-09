<?php

namespace Tests\Integration;

use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventSteps;
use PHPUnit\Framework\Attributes\DataProvider;

/** Revision (L2), review (L3) and SUN checker lists. */
class EventsModelRevisionListsTest extends EventsModelTestCase
{
    private int $translator;
    private int $reviser;
    private int $peer;
    private int $other;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translator = $this->member(['firstName' => 'John', 'lastName' => 'Smith']);
        $this->reviser = $this->member(['firstName' => 'Rose', 'lastName' => 'Green']);
        $this->peer = $this->member(['firstName' => 'Mary', 'lastName' => 'Jones']);
        $this->other = $this->member(['firstName' => 'Paul', 'lastName' => 'Brown']);
    }

    /** A translated event of $bookProject whose chapters 1-3 are assigned to the reviser at level 2 and 3. */
    private function translated(string $bookProject = 'ulb', string $bookCode = 'gen', array $project = []): int
    {
        $eventID = $this->event($this->project(['bookProject' => $bookProject, 'targetLang' => 'id'] + $project), ['bookCode' => $bookCode]);
        $trID = $this->translator($this->translator, $eventID, ['step' => EventSteps::FINISHED]);
        foreach ([1 => '[[1,2]]', 2 => '[[1]]', 3 => '[[2]]'] as $chapter => $chunks) {
            $this->chapter($trID, $chapter, ['chunks' => $chunks, 'done' => 1, 'l2memberID' => $this->reviser, 'l3memberID' => $this->reviser]);
        }
        return $eventID;
    }

    private function summary(array $events, array $fields): array
    {
        return array_map(fn($e) => array_map(fn($f) => $e->$f, $fields), $events);
    }

    // --- Revision (L2) checker's own events

    public function testCheckerRevisionEvents(): void
    {
        $eventID = $this->translated();
        $this->checkerL2($this->reviser, $eventID, ['currentChapter' => 2]);
        $this->checkerL2($this->other, $eventID);

        $events = $this->model->getCheckerRevisionEvents($this->reviser);

        $this->assertSame([[$eventID, 2, 'Genesis', 'Indonesian', '[[1]]']], $this->summary($events, ['eventID', 'currChkrs', 'name', 'tLang', 'chunks']));
    }

    public function testRevisionMemberEventWhileRevising(): void
    {
        $eventID = $this->translated();
        $this->checkerL2($this->reviser, $eventID, ['step' => EventCheckSteps::SELF_CHECK, 'currentChapter' => 2]);

        $this->assertSame(
            [[EventCheckSteps::SELF_CHECK, 2, 0, '[[1]]', 'Rose']],
            $this->summary($this->model->getRevisionMemberEvents($this->reviser), ['step', 'currentChapter', 'checkerID', 'chunks', 'firstName'])
        );
    }

    public function testPrayingReviserGetsTheirNextUncheckedChapter(): void
    {
        $eventID = $this->translated();
        self::db()->table('chapters')->where('chapter', 1)->update(['l2checked' => 1]);
        $this->checkerL2($this->reviser, $eventID, ['step' => EventCheckSteps::PRAY]);

        $this->assertSame(2, $this->model->getRevisionMemberEvents($this->reviser)[0]->currentChapter);
    }

    public function testReviserNotStartedCanBeLeftOut(): void
    {
        $this->checkerL2($this->reviser, $this->translated(), ['step' => EventCheckSteps::NONE]);

        $this->assertCount(1, $this->model->getRevisionMemberEvents($this->reviser));
        $this->assertSame([], $this->model->getRevisionMemberEvents($this->reviser, null, null, false, false));
    }

    public function testReviserInCheckingIsListedOncePerOpenCheckOnlyWithCheckers(): void
    {
        $this->checkerL2($this->reviser, $this->translated(), [
            'step' => EventCheckSteps::PEER_REVIEW,
            'peerCheck' => [1 => ['memberID' => $this->peer, 'done' => 1]],
            'kwCheck' => [2 => ['memberID' => 0, 'done' => 0]],
            'crCheck' => [3 => ['memberID' => $this->other, 'done' => 2]],
        ]);

        $this->assertSame([], $this->model->getRevisionMemberEvents($this->reviser), 'checking steps are hidden without checkers');

        $events = $this->model->getRevisionMemberEvents($this->reviser, null, null, true);
        $this->assertSame(
            [[EventCheckSteps::PEER_REVIEW, 1, $this->peer, true, '[[1,2]]'], [EventCheckSteps::KEYWORD_CHECK, 2, 0, false, '[[1]]']],
            $this->summary($events, ['step', 'currentChapter', 'checkerID', 'checkDone', 'chunks']),
            'finished checks (done 2) are left out'
        );
        $this->assertSame([['name' => 'Mary J.', 'id' => $this->peer]], $events[0]->checkers);
    }

    public function testSunReviserOnlySeesTheirOwnUnfinishedPeerChecks(): void
    {
        $this->checkerL2($this->reviser, $this->translated('sun'), [
            'step' => EventCheckSteps::PEER_REVIEW,
            'peerCheck' => [
                1 => ['memberID' => $this->reviser, 'done' => 0],
                2 => ['memberID' => $this->peer, 'done' => 0],
                3 => ['memberID' => $this->reviser, 'done' => 1],
            ],
        ]);

        $this->assertSame([1], array_map(fn($e) => $e->currentChapter, $this->model->getRevisionMemberEvents($this->reviser, null, null, true)));
    }

    // --- Revision approvers (the reviser's checkers)

    public function testRevisionCheckerGetsTheirOpenChecks(): void
    {
        $this->checkerL2($this->reviser, $this->translated(), [
            'peerCheck' => [1 => ['memberID' => $this->peer, 'done' => 0], 2 => ['memberID' => $this->peer, 'done' => 1], 3 => ['memberID' => $this->other, 'done' => 0]],
            'kwCheck' => [2 => ['memberID' => $this->peer, 'done' => 0]],
            'crCheck' => [3 => ['memberID' => $this->peer, 'done' => 0], 1 => ['memberID' => $this->peer, 'done' => 1]],
        ]);

        $this->assertSame([
            [EventCheckSteps::PEER_REVIEW, 1, $this->peer, 'l2'],
            [EventSteps::KEYWORD_CHECK, 2, $this->peer, 'l2'],
            [EventSteps::CONTENT_REVIEW, 3, $this->peer, 'l2'],
        ], $this->summary($this->model->getMemberEventsForRevisionChecker($this->peer), ['step', 'currentChapter', 'checkerID', 'manageMode']));
    }

    public function testRevisionCheckerListLeavesOutTheReviserAndSun(): void
    {
        $this->checkerL2($this->reviser, $this->translated(), ['kwCheck' => [1 => ['memberID' => $this->reviser, 'done' => 0]]]);
        $this->checkerL2($this->reviser, $this->translated('sun', 'exo'), ['peerCheck' => [1 => ['memberID' => $this->peer, 'done' => 0]]]);

        $this->assertSame([], $this->model->getMemberEventsForRevisionChecker($this->reviser));
        $this->assertSame([], $this->model->getMemberEventsForRevisionChecker($this->peer));
    }

    public function testSunRevisionChecker(): void
    {
        $this->checkerL2($this->reviser, $this->translated('sun'), ['peerCheck' => [
            1 => ['memberID' => $this->peer, 'done' => 0],
            2 => ['memberID' => $this->peer, 'done' => 1],
        ]]);
        $this->checkerL2($this->reviser, $this->translated('ulb', 'exo'), ['peerCheck' => [1 => ['memberID' => $this->peer, 'done' => 0]]]);

        $this->assertSame(
            [[EventCheckSteps::PEER_REVIEW, 1, 'Genesis']],
            $this->summary($this->model->getMemberEventsForSunRevisionChecker($this->peer), ['step', 'currentChapter', 'name'])
        );
    }

    // --- Review (L3)

    public function testCheckerL3Events(): void
    {
        $eventID = $this->translated();
        $this->checkerL3($this->reviser, $eventID, ['currentChapter' => 3]);
        $this->checkerL3($this->other, $eventID);

        $this->assertSame([[$eventID, 2, '[[2]]']], $this->summary($this->model->getCheckerL3Events($this->reviser), ['eventID', 'currChkrs', 'chunks']));
    }

    public function testReviewerAndTheirPeerReader(): void
    {
        $eventID = $this->translated();
        $this->checkerL3($this->reviser, $eventID, [
            'step' => EventCheckSteps::PEER_EDIT_L3,
            'currentChapter' => 1,
            'peerCheck' => [
                1 => ['memberID' => $this->peer, 'done' => 0],
                2 => ['memberID' => $this->peer, 'done' => 1],
                3 => ['memberID' => $this->peer, 'done' => 2],
            ],
        ]);

        $this->assertSame(
            [[EventCheckSteps::PEER_EDIT_L3, 1, 'l3']],
            $this->summary($this->model->getMemberEventsForCheckerL3($this->reviser), ['step', 'currentChapter', 'manageMode'])
        );
        $this->assertSame(
            [[EventCheckSteps::PEER_REVIEW_L3, 1, EventCheckSteps::PEER_EDIT_L3, 'Rose', $this->peer, $this->reviser],
             [EventCheckSteps::PEER_EDIT_L3, 2, EventCheckSteps::PEER_EDIT_L3, 'Rose', $this->peer, $this->reviser]],
            $this->summary($this->model->getMemberEventsForCheckerL3($this->peer), ['step', 'currentChapter', 'peerStep', 'checkerFName', 'myChkMemberID', 'chkMemberID']),
            'finished readings (done 2) are left out'
        );
        $this->assertSame([2], array_map(fn($e) => $e->currentChapter, $this->model->getMemberEventsForCheckerL3($this->peer, $eventID, null, 2)));
    }

    public function testPrayingReviewerGetsTheirNextUncheckedChapterAndUnstartedOnesAreLeftOut(): void
    {
        $eventID = $this->translated();
        self::db()->table('chapters')->where('chapter', 1)->update(['l3checked' => 1]);
        $this->checkerL3($this->reviser, $eventID, ['step' => EventCheckSteps::PRAY]);
        $this->checkerL3($this->other, $this->translated('ulb', 'exo'), ['step' => EventCheckSteps::NONE]);

        $this->assertSame([2], array_map(fn($e) => $e->currentChapter, $this->model->getMemberEventsForCheckerL3($this->reviser)));
        $this->assertSame([], $this->model->getMemberEventsForCheckerL3($this->other));
    }

    // --- SUN theological and verse-by-verse checkers

    private function sunChecks(string $bookCode = 'gen', string $sourceBible = 'ulb'): int
    {
        $eventID = $this->event($this->project(['bookProject' => 'sun', 'sourceBible' => $sourceBible]), ['bookCode' => $bookCode]);
        $this->translator($this->translator, $eventID, [
            'step' => EventSteps::SELF_CHECK,
            'currentChapter' => 4,
            'kwCheck' => [1 => ['memberID' => $this->peer, 'done' => 0], 2 => ['memberID' => $this->peer, 'done' => 1], 3 => ['memberID' => $this->other, 'done' => 0]],
            'crCheck' => [1 => ['memberID' => $this->peer, 'done' => 0], 2 => ['memberID' => $this->peer, 'done' => 1], 3 => ['memberID' => $this->peer, 'done' => 2]],
        ]);
        return $eventID;
    }

    public static function sunLists(): array
    {
        return [['getMemberEventsForCheckerSun'], ['getMemberEventsForSun']];
    }

    #[DataProvider('sunLists')]
    public function testSunCheckerGetsTheirOpenChecks(string $method): void
    {
        $this->sunChecks();

        $this->assertSame([
            [EventSteps::THEO_CHECK, 1, 0, $this->peer],
            [EventSteps::CONTENT_REVIEW, 1, 0, $this->peer],
            [EventSteps::FINAL_REVIEW, 2, 0, $this->peer],
        ], $this->summary($this->model->$method($this->peer), ['step', 'currentChapter', 'myMemberID', 'myChkMemberID']));
    }

    public function testSunCheckerListEndsOdbVerseByVerseCheckAtDoneOne(): void
    {
        $this->sunChecks('gen', 'odb');

        $vbvChapters = array_map(
            fn($e) => $e->currentChapter,
            array_filter($this->model->getMemberEventsForCheckerSun($this->peer), fn($e) => $e->step != EventSteps::THEO_CHECK)
        );

        // ODB has no final review: a verse-by-verse check is finished at done 1
        $this->assertContains(1, $vbvChapters);
        $this->assertNotContains(2, $vbvChapters);
    }

    public function testSunCheckerListOnlyHasTranslatorsWithTheoChecks(): void
    {
        $eventID = $this->event($this->project(['bookProject' => 'sun']));
        $this->translator($this->translator, $eventID, ['crCheck' => [1 => ['memberID' => $this->peer, 'done' => 0]]]);

        $this->assertSame([], $this->model->getMemberEventsForCheckerSun($this->peer));
        $this->assertCount(1, $this->model->getMemberEventsForSun($this->peer));
    }

    public function testSunTranslatorSeesTheirOwnTranslation(): void
    {
        $this->sunChecks();

        $this->assertSame([[EventSteps::SELF_CHECK, 4]], $this->summary($this->model->getMemberEventsForSun($this->translator), ['step', 'currentChapter']));
        $this->assertSame([], $this->model->getMemberEventsForSun($this->translator, null, null, 1));
    }

    // --- Each event keeps its own admins

    public function testEachListedEventHasItsOwnAdmins(): void
    {
        $genesis = $this->translated();
        $exodus = $this->translated('ulb', 'exo');
        $this->eventAdmin($genesisAdmin = $this->member(), $genesis);
        $this->eventAdmin($exodusAdmin = $this->member(), $exodus);
        $expected = ['Genesis' => [$genesisAdmin], 'Exodus' => [$exodusAdmin]];

        foreach ([$genesis, $exodus] as $eventID) {
            $this->checkerL2($this->reviser, $eventID, ['step' => EventCheckSteps::SELF_CHECK]);
            $this->checkerL3($this->reviser, $eventID, ['step' => EventCheckSteps::PEER_REVIEW_L3]);
        }

        $this->assertSame($expected, $this->adminsByBook($this->model->getRevisionMemberEvents($this->reviser)), 'revision');
        $this->assertSame($expected, $this->adminsByBook($this->model->getMemberEventsForCheckerL3($this->reviser)), 'review');

        $sunGenesis = $this->sunChecks();
        $sunExodus = $this->sunChecks('exo');
        $this->eventAdmin($genesisAdmin, $sunGenesis);
        $this->eventAdmin($exodusAdmin, $sunExodus);
        $this->assertSame($expected, $this->adminsByBook($this->model->getMemberEventsForCheckerSun($this->peer)), 'sun');
    }
}
