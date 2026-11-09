<?php

namespace Tests\Unit;

use App\Domain\AnyL3Progress;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;
use Support\Collection;

class AnyL3ProgressTest extends TestCase
{
    /** L3 checker 10 on $step for $currentChapter, with chapter 1's peer check if given. */
    private function checker(string $step, int $currentChapter = 1, ?array $peer = null): object
    {
        return (object)[
            'currentChapter' => $currentChapter,
            'step' => $step,
            'peerCheck' => $peer ? json_encode([1 => $peer]) : null,
        ];
    }

    /** Book of 2 chapters; chapter 1 is assigned to L3 checker 10, chapter 2 is not assigned. */
    private function event(object $checker, string $bookProject = 'ulb'): object
    {
        $chapter = fn($n, $l3memberID, $checkerL3) => (object)[
            'chapter' => $n,
            'l3chID' => $n,
            'l3memberID' => $l3memberID,
            'l3checked' => false,
            'checkerL3' => $checkerL3,
        ];

        return (object)[
            'project' => (object)['bookProject' => $bookProject],
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [$chapter(1, 10, $checker), $chapter(2, 0, null)],
            'chunks' => new Collection([
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-02 10:00:00'],
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-01 10:00:00'],
                (object)['chapter' => 2, 'dateUpdate' => '2024-02-01 10:00:00'],
            ]),
        ];
    }

    private function chapter1(object $checker): array
    {
        return AnyL3Progress::calculateEventProgress($this->event($checker))['chapters'][1];
    }

    private function states(array $ch): array
    {
        return [$ch['peerReview']['state'], $ch['peerEdit']['state'], $ch['progress']];
    }

    public function testChaptersAndLastEdit(): void
    {
        $data = AnyL3Progress::calculateEventProgress($this->event($this->checker(EventCheckSteps::PRAY)));

        $this->assertSame([1, 2], array_keys($data['chapters']));
        $this->assertSame('2024-01-02 10:00:00', $data['chapters'][1]['lastEdit'], 'latest edit wins regardless of order');
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testTnBooksIncludeTheIntroChapter(): void
    {
        $data = AnyL3Progress::calculateEventProgress($this->event($this->checker(EventCheckSteps::PRAY), 'tn'));

        $this->assertSame([0, 1, 2], array_keys($data['chapters']));
    }

    public function testUnassignedChapterHasNoProgress(): void
    {
        $data = AnyL3Progress::calculateEventProgress($this->event($this->checker(EventCheckSteps::PRAY)));

        $this->assertArrayNotHasKey('progress', $data['chapters'][2]);
    }

    public function testNotStarted(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::PRAY));

        $this->assertSame([S::NOT_STARTED, S::NOT_STARTED, 0], $this->states($ch));
        $this->assertSame('na', $ch['peerChk']['checkerID']);
    }

    public function testPeerReviewWaitingForChecker(): void
    {
        $noEntry = $this->chapter1($this->checker(EventCheckSteps::PEER_REVIEW_L3));
        $noChecker = $this->chapter1($this->checker(EventCheckSteps::PEER_REVIEW_L3, 1, ['memberID' => 0, 'done' => 0]));
        $otherChapter = $this->chapter1($this->checker(EventCheckSteps::PEER_REVIEW_L3, 2));

        $this->assertSame([S::WAITING, S::NOT_STARTED, 0], $this->states($noEntry));
        $this->assertSame([S::WAITING, S::NOT_STARTED, 0], $this->states($noChecker));
        $this->assertSame([S::NOT_STARTED, S::NOT_STARTED, 0], $this->states($otherChapter));
    }

    public function testPeerReviewInProgress(): void
    {
        $data = AnyL3Progress::calculateEventProgress($this->event(
            $this->checker(EventCheckSteps::PEER_REVIEW_L3, 1, ['memberID' => 20, 'done' => 0])
        ));
        $ch = $data['chapters'][1];

        $this->assertSame([S::IN_PROGRESS, S::NOT_STARTED, 0], $this->states($ch));
        $this->assertSame(20, $ch['peerChk']['checkerID']);
        $this->assertSame([10, 20], array_keys($data['members']));
    }

    public function testPeerReviewChecked(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::PEER_REVIEW_L3, 1, ['memberID' => 20, 'done' => 1]));

        $this->assertSame([S::CHECKED, S::NOT_STARTED, 25], $this->states($ch));
    }

    public function testPeerEditInProgress(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::PEER_EDIT_L3, 1, ['memberID' => 20, 'done' => 1]));

        $this->assertSame([S::FINISHED, S::IN_PROGRESS, 50], $this->states($ch));
    }

    public function testPeerEditCheckedWhileStillOnChapter(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::PEER_EDIT_L3, 1, ['memberID' => 20, 'done' => 2]));

        $this->assertSame([S::FINISHED, S::CHECKED, 75], $this->states($ch));
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->checker(EventCheckSteps::FINISHED, 0, ['memberID' => 20, 'done' => 2]));

        $data = AnyL3Progress::calculateEventProgress($event);

        $this->assertSame([S::FINISHED, S::FINISHED, 100], $this->states($data['chapters'][1]));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, AnyL3Progress::calculateEventProgress($event, true));
    }
}
