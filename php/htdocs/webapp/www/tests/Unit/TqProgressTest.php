<?php

namespace Tests\Unit;

use App\Domain\TqProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
use PHPUnit\Framework\TestCase;

class TqProgressTest extends TestCase
{
    /**
     * Event with 2 chapters; chapter 1 assigned to member 10 and drafted.
     */
    private function event(string $step, int $currentChapter, array $otherCheck = [], array $peerCheck = []): object
    {
        $translator = (object)[
            'memberID' => 10,
            'step' => $step,
            'currentChapter' => $currentChapter,
            'otherCheck' => json_encode($otherCheck),
            'peerCheck' => json_encode($peerCheck),
        ];

        return (object)[
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [(object)[
                'chapter' => 1,
                'trID' => 7,
                'memberID' => 10,
                'chunks' => json_encode([[1, 2], [3]]),
                'done' => false,
                'translator' => $translator,
            ]],
            'chunks' => [
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-01 10:00:00'],
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-02 10:00:00'],
            ],
        ];
    }

    public function testChapterInMultiDraftIsInProgress(): void
    {
        $data = TqProgress::calculateEventProgress($this->event(EventSteps::MULTI_DRAFT, 1));
        $ch = $data['chapters'][1];

        $this->assertSame(StepsStates::IN_PROGRESS, $ch['multi']['state']);
        $this->assertSame(StepsStates::NOT_STARTED, $ch['selfEdit']['state']);
        $this->assertSame(0, $ch['progress']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit']);
        $this->assertSame([], $data['chapters'][2]);
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testSelfCheckFinishesDraft(): void
    {
        $data = TqProgress::calculateEventProgress($this->event(EventSteps::SELF_CHECK, 1));

        $this->assertSame(StepsStates::FINISHED, $data['chapters'][1]['multi']['state']);
        $this->assertSame(StepsStates::IN_PROGRESS, $data['chapters'][1]['selfEdit']['state']);
        $this->assertSame(25, $data['chapters'][1]['progress']);
    }

    public function testWaitingForChecker(): void
    {
        $event = $this->event(EventSteps::FINISHED, 0, [1 => ['memberID' => 0, 'done' => 0]]);
        $ch = TqProgress::calculateEventProgress($event)['chapters'][1];

        $this->assertSame(StepsStates::WAITING, $ch['kwc']['state']);
        $this->assertSame(50, $ch['progress']);
    }

    public function testPeerCheckWaitingAfterKeywordCheck(): void
    {
        $event = $this->event(EventSteps::FINISHED, 0, [1 => ['memberID' => 20, 'done' => 2]]);
        $data = TqProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        $this->assertSame(StepsStates::FINISHED, $ch['kwc']['state']);
        $this->assertSame(StepsStates::WAITING, $ch['peerChk']['state']);
        $this->assertSame(20, $ch['checkerID']);
        $this->assertSame(75, $ch['progress']);
        $this->assertSame([10 => '', 20 => ''], $data['members']);
    }

    public function testPeerCheckedAddsPartialProgress(): void
    {
        $event = $this->event(
            EventSteps::FINISHED, 0,
            [1 => ['memberID' => 20, 'done' => 2]],
            [1 => ['memberID' => 30, 'done' => 1]]
        );
        $ch = TqProgress::calculateEventProgress($event)['chapters'][1];

        $this->assertSame(StepsStates::CHECKED, $ch['peerChk']['state']);
        $this->assertSame(30, $ch['peerChk']['checkerID']);
        $this->assertSame(87, $ch['progress']);
    }

    public function testFullyCheckedChapterIs100AndOverallIsAveragedOverAllChapters(): void
    {
        $event = $this->event(
            EventSteps::FINISHED, 0,
            [1 => ['memberID' => 20, 'done' => 3]],
            [1 => ['memberID' => 30, 'done' => 1]]
        );

        $data = TqProgress::calculateEventProgress($event);

        $this->assertSame(100, $data['chapters'][1]['progress']);
        $this->assertSame(EventSteps::FINISHED, $data['chapters'][1]['stepChk']);
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, TqProgress::calculateEventProgress($event, true));
    }
}
