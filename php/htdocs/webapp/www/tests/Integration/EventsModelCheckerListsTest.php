<?php

namespace Tests\Integration;

use Helpers\Constants\EventSteps;
use Helpers\Constants\ResourcesCheckSteps;
use Helpers\Constants\TnCheckSteps;
use PHPUnit\Framework\Attributes\DataProvider;

/** The lists of chapters a checker has to check: scripture (L1), notes, other help tools and radio. */
class EventsModelCheckerListsTest extends EventsModelTestCase
{
    private int $translator;
    private int $checker;
    private int $other;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translator = $this->member(['firstName' => 'John', 'lastName' => 'Smith']);
        $this->checker = $this->member(['firstName' => 'Mary', 'lastName' => 'Jones']);
        $this->other = $this->member(['firstName' => 'Paul', 'lastName' => 'Brown']);
    }

    /** An event of $bookProject where $this->translator has the given check entries. */
    private function translating(string $bookProject, array $checks, string $bookCode = 'gen'): int
    {
        $eventID = $this->event($this->project(['bookProject' => $bookProject, 'targetLang' => 'id']), ['bookCode' => $bookCode]);
        $trID = $this->translator($this->translator, $eventID, ['step' => EventSteps::FINISHED] + $checks);
        $this->chapter($trID, 1, ['chunks' => '[[1,2]]']);
        $this->chapter($trID, 2, ['chunks' => '[[1]]']);
        return $eventID;
    }

    private function summary(array $events, array $fields): array
    {
        return array_map(fn($e) => array_map(fn($f) => $e->$f, $fields), $events);
    }

    // --- Scripture (L1) checkers

    public function testScriptureCheckerGetsTheirOpenChecks(): void
    {
        $this->translating('ulb', [
            'peerCheck' => [
                1 => ['memberID' => $this->checker, 'done' => 0],
                2 => ['memberID' => $this->checker, 'done' => 1],
                3 => ['memberID' => $this->other, 'done' => 0],
            ],
            'kwCheck' => [2 => ['memberID' => $this->checker, 'done' => 0]],
            'crCheck' => [
                1 => ['memberID' => $this->other, 'done' => 0, 'memberID2' => $this->checker, 'done2' => 0],
                2 => ['memberID' => $this->checker, 'done' => 0, 'memberID2' => $this->other, 'done2' => 0],
                3 => ['memberID' => $this->other, 'done' => 0, 'memberID2' => $this->checker, 'done2' => 1],
            ],
        ]);

        $events = $this->model->getMemberEventsForChecker($this->checker);

        $this->assertSame([
            [EventSteps::PEER_REVIEW, 1, $this->checker],
            [EventSteps::KEYWORD_CHECK, 2, $this->checker],
            [EventSteps::CONTENT_REVIEW, 1, $this->checker],
            [EventSteps::CONTENT_REVIEW, 2, $this->checker],
        ], $this->summary($events, ['step', 'currentChapter', 'checkerID']), 'finished checks and other checkers\' are left out');
        $this->assertSame([2, 1], [$events[2]->vChecker, $events[3]->vChecker], 'which of the two verse-by-verse checkers');
        $this->assertSame(['Genesis', 'Indonesian', 'John'], [$events[0]->bookName, $events[0]->tLang, $events[0]->firstName]);
    }

    public function testScriptureCheckerFilters(): void
    {
        $genesis = $this->translating('ulb', ['peerCheck' => [1 => ['memberID' => $this->checker, 'done' => 0], 2 => ['memberID' => $this->checker, 'done' => 0]]]);
        $this->translating('ulb', ['peerCheck' => [1 => ['memberID' => $this->checker, 'done' => 0]]], 'exo');

        $chapters = fn(...$args) => array_map(fn($e) => [$e->bookName, $e->currentChapter], $this->model->getMemberEventsForChecker($this->checker, ...$args));

        $this->assertSame([['Genesis', 1], ['Genesis', 2]], $chapters($genesis));
        $this->assertSame([['Genesis', 2]], $chapters($genesis, $this->translator, 2));
        $this->assertSame([], $chapters($genesis, $this->other));
        $this->assertSame('[[1]]', $this->model->getMemberEventsForChecker($this->checker, $genesis, null, 2)[0]->chunks);
    }

    public function testScriptureCheckerListLeavesOutTheirOwnTranslationAndOtherProjects(): void
    {
        $this->translating('ulb', ['peerCheck' => [1 => ['memberID' => $this->translator, 'done' => 0]]]);
        $this->translating('tn', ['peerCheck' => [1 => ['memberID' => $this->checker, 'done' => 0]]], 'exo');

        $this->assertSame([], $this->model->getMemberEventsForChecker($this->translator));
        $this->assertSame([], $this->model->getMemberEventsForChecker($this->checker));
    }

    // --- Notes

    public static function tnSteps(): array
    {
        return [
            [TnCheckSteps::PRAY, EventSteps::PRAY],
            [TnCheckSteps::CONSUME, EventSteps::CONSUME],
            [TnCheckSteps::HIGHLIGHT, EventSteps::HIGHLIGHT],
            [TnCheckSteps::SELF_CHECK, EventSteps::SELF_CHECK],
            [TnCheckSteps::KEYWORD_CHECK, EventSteps::KEYWORD_CHECK],
            [TnCheckSteps::PEER_REVIEW, EventSteps::PEER_REVIEW],
        ];
    }

    #[DataProvider('tnSteps')]
    public function testNotesCheckerStep(int $done, string $step): void
    {
        $this->translating('tn', ['otherCheck' => [1 => ['memberID' => $this->checker, 'done' => $done]]]);

        $this->assertSame([[$step, 1]], $this->summary($this->model->getMemberEventsForNotes($this->checker), ['step', 'currentChapter']));
    }

    public function testNotesCheckerAndPeerChecker(): void
    {
        $this->translating('tn', [
            'otherCheck' => [
                1 => ['memberID' => $this->checker, 'done' => TnCheckSteps::PEER_REVIEW],
                2 => ['memberID' => $this->checker, 'done' => TnCheckSteps::FINISHED],
                3 => ['memberID' => $this->other, 'done' => TnCheckSteps::CONSUME],
            ],
            'peerCheck' => [1 => ['memberID' => $this->other, 'done' => 0]],
        ]);

        $edits = $this->model->getMemberEventsForNotes($this->checker);
        $this->assertSame(
            [[EventSteps::PEER_REVIEW, 1, 1, $this->other, 'Paul', false, 0, $this->checker, 'tn']],
            $this->summary($edits, ['step', 'currentChapter', 'peer', 'checkerID', 'checkerFName', 'checkDone', 'myMemberID', 'myChkMemberID', 'manageMode']),
            'finished chapters and other checkers\' are left out'
        );

        $peerChecks = $this->model->getMemberEventsForNotes($this->other, null, null, null, 'check');
        $this->assertSame(
            [[EventSteps::PEER_REVIEW, 1, 2, $this->checker, 'Mary', $this->other]],
            $this->summary($peerChecks, ['step', 'currentChapter', 'peer', 'checkerID', 'checkerFName', 'myMemberID'])
        );
        $this->assertSame([3], array_map(fn($e) => $e->currentChapter,
            $this->model->getMemberEventsForNotes($this->other, null, null, null, 'edit')));
    }

    public function testNotesPeerCheckDone(): void
    {
        $this->translating('tn', [
            'otherCheck' => [1 => ['memberID' => $this->checker, 'done' => TnCheckSteps::PEER_REVIEW]],
            'peerCheck' => [1 => ['memberID' => $this->other, 'done' => 1]],
        ]);

        $this->assertTrue($this->model->getMemberEventsForNotes($this->checker)[0]->checkDone);
        $this->assertSame([], $this->model->getMemberEventsForNotes($this->other, null, null, null, 'check'));
    }

    // --- Questions, words and other help tools

    public static function resourceSteps(): array
    {
        return [
            [ResourcesCheckSteps::PRAY, EventSteps::PRAY],
            [ResourcesCheckSteps::KEYWORD_CHECK, EventSteps::KEYWORD_CHECK],
            [ResourcesCheckSteps::PEER_REVIEW, EventSteps::PEER_REVIEW],
        ];
    }

    #[DataProvider('resourceSteps')]
    public function testOtherCheckerStep(int $done, string $step): void
    {
        $this->translating('tq', ['otherCheck' => [
            1 => ['memberID' => $this->checker, 'done' => $done],
            2 => ['memberID' => $this->checker, 'done' => ResourcesCheckSteps::FINISHED],
        ]]);

        $this->assertSame([[$step, 1, 'tq']], $this->summary($this->model->getMemberEventsForOther($this->checker), ['step', 'currentChapter', 'manageMode']));
    }

    public function testOtherPeerChecker(): void
    {
        $this->translating('obs', [
            'otherCheck' => [1 => ['memberID' => $this->checker, 'done' => ResourcesCheckSteps::PEER_REVIEW]],
            'peerCheck' => [1 => ['memberID' => $this->other, 'done' => 0]],
        ]);

        $this->assertSame(
            [[EventSteps::PEER_REVIEW, 1, 2, $this->checker, 'Mary']],
            $this->summary($this->model->getMemberEventsForOther($this->other), ['step', 'currentChapter', 'peer', 'checkerID', 'checkerFName'])
        );
        $this->assertSame([], $this->model->getMemberEventsForOther($this->other, null, null, null, 'edit'));
    }

    public function testWordsCheckerGetsTheGroupsWordsAndBcaCheckerTheWord(): void
    {
        $twEvent = $this->event($this->project(['bookProject' => 'tw']), ['bookCode' => 'wkt']);
        $groupID = $this->wordGroup($twEvent, ['god', 'love']);
        $this->translator($this->translator, $twEvent, ['otherCheck' => [$groupID => ['memberID' => $this->checker, 'done' => 0]]]);

        $bcaEvent = $this->event($this->project(['bookProject' => 'bca']));
        $wordID = $this->word($bcaEvent, 'Abraham');
        $this->translator($this->translator, $bcaEvent, ['peerCheck' => [$wordID => ['memberID' => $this->checker, 'done' => 0]],
            'otherCheck' => [$wordID => ['memberID' => $this->other, 'done' => ResourcesCheckSteps::PEER_REVIEW]]]);

        $events = $this->model->getMemberEventsForOther($this->checker);

        $this->assertSame([[$groupID, '["god","love"]'], [$wordID, 'Abraham']], [
            [$events[0]->currentChapter, $events[0]->words],
            [$events[1]->currentChapter, $events[1]->word],
        ]);
    }

    // --- Radio

    public function testRadioPeerChecker(): void
    {
        $eventID = $this->translating('rad', ['peerCheck' => [
            1 => ['memberID' => $this->checker, 'done' => 0],
            2 => ['memberID' => $this->checker, 'done' => 1],
            3 => ['memberID' => $this->other, 'done' => 0],
        ]]);

        $this->assertSame(
            [[EventSteps::PEER_REVIEW, 1, $this->checker, true]],
            $this->summary($this->model->getMemberEventsForRadio($this->checker), ['step', 'currentChapter', 'myChkMemberID', 'isCheckerPage'])
        );
        $this->assertSame([], $this->model->getMemberEventsForRadio($this->checker, $eventID, null, 2));
    }

    // --- Each event keeps its own admins

    public static function lists(): array
    {
        $peer = fn($self) => ['peerCheck' => [1 => ['memberID' => $self->checker, 'done' => 0]]];
        $edit = fn($self) => ['otherCheck' => [1 => ['memberID' => $self->checker, 'done' => 0]]];

        return [
            'scripture' => ['ulb', 'getMemberEventsForChecker', 'bookName', $peer],
            'notes' => ['tn', 'getMemberEventsForNotes', 'name', $edit],
            'questions' => ['tq', 'getMemberEventsForOther', 'name', $edit],
            'radio' => ['rad', 'getMemberEventsForRadio', 'name', $peer],
        ];
    }

    #[DataProvider('lists')]
    public function testEachListedEventHasItsOwnAdmins(string $bookProject, string $method, string $nameField, \Closure $checks): void
    {
        $genesis = $this->translating($bookProject, $checks($this));
        $exodus = $this->translating($bookProject, $checks($this), 'exo');
        $this->eventAdmin($genesisAdmin = $this->member(), $genesis);
        $this->eventAdmin($exodusAdmin = $this->member(), $exodus);

        $this->assertSame(
            ['Genesis' => [$genesisAdmin], 'Exodus' => [$exodusAdmin]],
            $this->adminsByBook($this->model->$method($this->checker), $nameField)
        );
    }
}
