<?php

namespace Tests\Unit;

use App\Domain\SunRevisionProgress;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;
use Support\Collection;

/** Consume and self edit are 33% each, peer check 34%. */
class SunRevisionProgressTest extends TestCase
{
    /** L2 checker 10 on $step for $currentChapter, with chapter 1's peer check if given. */
    private function checker(string $step = EventCheckSteps::FINISHED, int $currentChapter = 0, ?array $peer = null): object
    {
        return (object)[
            'currentChapter' => $currentChapter,
            'step' => $step,
            'peerCheck' => $peer ? json_encode([1 => $peer]) : null,
        ];
    }

    /** Book of 2 chapters; chapter 1 is assigned to L2 checker 10, chapter 2 is not assigned. */
    private function event(object $checker): object
    {
        $chapter = fn($n, $l2memberID, $checkerL2) => (object)[
            'chapter' => $n,
            'l2chID' => $n,
            'l2memberID' => $l2memberID,
            'l2checked' => false,
            'checkerL2' => $checkerL2,
        ];

        return (object)[
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [$chapter(1, 10, $checker), $chapter(2, 0, null)],
            'chunks' => new Collection([
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-02 10:00:00'],
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-01 10:00:00'],
            ]),
        ];
    }

    private function chapter1(object $checker): array
    {
        return SunRevisionProgress::calculateEventProgress($this->event($checker))['chapters'][1];
    }

    private function states(array $ch): array
    {
        return [$ch['consume']['state'], $ch['selfEdit']['state'], $ch['peer']['state'], $ch['progress']];
    }

    public function testNotStarted(): void
    {
        $data = SunRevisionProgress::calculateEventProgress($this->event($this->checker(EventCheckSteps::PRAY, 1)));
        $ch = $data['chapters'][1];

        $this->assertSame([S::NOT_STARTED, S::NOT_STARTED, S::NOT_STARTED, 0], $this->states($ch));
        $this->assertSame('na', $ch['peer']['checkerID']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit'], 'latest edit wins regardless of order');
        $this->assertArrayNotHasKey('progress', $data['chapters'][2], 'unassigned chapter');
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testConsumeInProgress(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::CONSUME, 1));

        $this->assertSame([S::IN_PROGRESS, S::NOT_STARTED, S::NOT_STARTED, 0], $this->states($ch));
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::SELF_CHECK, 1));

        $this->assertSame([S::FINISHED, S::IN_PROGRESS, S::NOT_STARTED, 33], $this->states($ch));
    }

    public function testStepOfAnotherChapterIsIgnored(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::SELF_CHECK, 2));

        $this->assertSame([S::NOT_STARTED, S::NOT_STARTED, S::NOT_STARTED, 0], $this->states($ch));
    }

    public function testPeerCheckWaitingForChecker(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::FINISHED, 0, ['memberID' => 0, 'done' => 0]));

        $this->assertSame([S::FINISHED, S::FINISHED, S::WAITING, 66], $this->states($ch));
        $this->assertSame(0, $ch['peer']['checkerID']);
    }

    public function testPeerCheckInProgress(): void
    {
        $data = SunRevisionProgress::calculateEventProgress(
            $this->event($this->checker(EventCheckSteps::FINISHED, 0, ['memberID' => 20, 'done' => 0]))
        );
        $ch = $data['chapters'][1];

        $this->assertSame([S::FINISHED, S::FINISHED, S::IN_PROGRESS, 66], $this->states($ch));
        $this->assertSame(20, $ch['peer']['checkerID']);
        $this->assertSame([10, 20], array_keys($data['members']));
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->checker(EventCheckSteps::FINISHED, 0, ['memberID' => 20, 'done' => 1]));

        $data = SunRevisionProgress::calculateEventProgress($event);

        $this->assertSame([S::FINISHED, S::FINISHED, S::FINISHED, 100], $this->states($data['chapters'][1]));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, SunRevisionProgress::calculateEventProgress($event, true));
    }
}
