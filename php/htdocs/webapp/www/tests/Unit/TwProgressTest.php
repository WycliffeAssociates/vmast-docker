<?php

namespace Tests\Unit;

use App\Domain\TwProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;

class TwProgressTest extends TestCase
{
    private function translator(array $overrides = []): object
    {
        return (object)array_merge([
            'memberID' => 10,
            'step' => EventSteps::FINISHED,
            'currentChapter' => 0,
            'otherCheck' => null,
            'peerCheck' => null,
        ], array_map(fn($v) => is_array($v) ? json_encode($v) : $v, $overrides));
    }

    /**
     * TW "chapters" are word groups and only assigned groups exist, so progress is averaged over them.
     * Group 5 belongs to $translator; group 6, when $withSecondGroup, is assigned but not started.
     */
    private function event(object $translator, bool $drafted = false, bool $withSecondGroup = false): object
    {
        $group = fn($id, $tr, $chunks) => (object)[
            'chapter' => $id,
            'trID' => 7,
            'memberID' => $tr->memberID,
            'chunks' => $chunks,
            'done' => false,
            'translator' => $tr,
            'wordGroup' => (object)['words' => json_encode(['god', 'love'])],
        ];

        $chapters = [$group(5, $translator, $drafted ? json_encode([['god'], ['love']]) : null)];
        if ($withSecondGroup) {
            $chapters[] = $group(6, $this->translator(['memberID' => 11]), null);
        }

        return (object)[
            'chapters' => $chapters,
            'chunks' => $drafted ? [
                (object)['chapter' => 5, 'dateUpdate' => '2024-01-02 10:00:00'],
                (object)['chapter' => 5, 'dateUpdate' => '2024-01-01 10:00:00'],
            ] : [],
        ];
    }

    private function group5(object $translator, bool $drafted = false): array
    {
        return TwProgress::calculateEventProgress($this->event($translator, $drafted))['chapters'][5];
    }

    private function checking(int $done, ?array $peer = null, int $checker = 30): array
    {
        return $this->group5($this->translator([
            'otherCheck' => [5 => ['memberID' => $checker, 'done' => $done]],
            'peerCheck' => $peer ? [5 => $peer] : null,
        ]), true);
    }

    public function testMultiDraftBeforeAnyWordTranslated(): void
    {
        $data = TwProgress::calculateEventProgress(
            $this->event($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 5]))
        );
        $ch = $data['chapters'][5];

        $this->assertSame(['god', 'love'], $ch['words']);
        $this->assertSame(EventSteps::MULTI_DRAFT, $ch['step']);
        $this->assertSame(S::IN_PROGRESS, $ch['multi']['state']);
        $this->assertSame(0, $ch['progress']);
        $this->assertSame([], $ch['chunksData']);
        $this->assertSame([5], array_keys($data['chapters']), 'only assigned groups are listed');
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testEventWithoutAssignedGroupsIsZero(): void
    {
        $event = (object)['chapters' => [], 'chunks' => []];

        $this->assertEquals(0, TwProgress::calculateEventProgress($event, true));
        $this->assertSame([], TwProgress::calculateEventProgress($event)['chapters']);
    }

    public function testGroupNotCurrentAndNotStartedStaysAtPray(): void
    {
        $ch = $this->group5($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 6]));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(S::NOT_STARTED, $ch['multi']['state']);
    }

    public function testMultiDraftWithTranslatedWords(): void
    {
        $ch = $this->group5($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 5]), true);

        $this->assertSame(S::IN_PROGRESS, $ch['multi']['state']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit'], 'latest edit wins regardless of order');
        $this->assertSame(0, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->group5($this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 5]), true);

        $this->assertSame(S::FINISHED, $ch['multi']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertSame(25, $ch['progress']);
    }

    public function testCheckWaitingForChecker(): void
    {
        $ch = $this->checking(0, null, 0);

        $this->assertSame(EventSteps::FINISHED, $ch['step']);
        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::WAITING, $ch['kwc']['state']);
        $this->assertArrayNotHasKey('checkerID', $ch);
        $this->assertSame(50, $ch['progress']);
    }

    public function testKeywordCheckInProgress(): void
    {
        $ch = $this->checking(1);

        $this->assertSame(S::IN_PROGRESS, $ch['kwc']['state']);
        $this->assertSame(30, $ch['checkerID']);
        $this->assertSame(50, $ch['progress']);
    }

    public function testPeerCheckWaiting(): void
    {
        foreach ([$this->checking(2), $this->checking(2, ['memberID' => 0, 'done' => 0])] as $ch) {
            $this->assertSame(S::FINISHED, $ch['kwc']['state']);
            $this->assertSame(S::WAITING, $ch['peerChk']['state']);
            $this->assertSame('na', $ch['peerChk']['checkerID']);
            $this->assertSame(75, $ch['progress']);
        }
    }

    public function testPeerCheckInProgress(): void
    {
        $ch = $this->checking(2, ['memberID' => 40, 'done' => 0]);

        $this->assertSame(S::IN_PROGRESS, $ch['peerChk']['state']);
        $this->assertSame(40, $ch['peerChk']['checkerID']);
        $this->assertSame(75, $ch['progress']);
    }

    public function testPeerCheckChecked(): void
    {
        $ch = $this->checking(2, ['memberID' => 40, 'done' => 1]);

        $this->assertSame(S::CHECKED, $ch['peerChk']['state']);
        $this->assertSame(87, $ch['progress']);
    }

    public function testFullyCheckedGroupIs100AndOverallIsAveragedOverAssignedGroups(): void
    {
        $event = $this->event($this->translator([
            'otherCheck' => [5 => ['memberID' => 30, 'done' => 3]],
            'peerCheck' => [5 => ['memberID' => 40, 'done' => 1]],
        ]), true, true);

        $data = TwProgress::calculateEventProgress($event);
        $ch = $data['chapters'][5];

        foreach (['multi', 'selfEdit', 'kwc', 'peerChk'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertSame(EventSteps::FINISHED, $ch['stepChk']);
        $this->assertSame(100, $ch['progress']);
        $this->assertSame(0, $data['chapters'][6]['progress']);
        $this->assertSame([10, 11, 30, 40], array_keys($data['members']));
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, TwProgress::calculateEventProgress($event, true));
    }
}
