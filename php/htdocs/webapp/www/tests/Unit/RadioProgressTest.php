<?php

namespace Tests\Unit;

use App\Domain\RadioProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;

class RadioProgressTest extends TestCase
{
    private function translator(array $overrides = []): object
    {
        return (object)array_merge([
            'memberID' => 10,
            'step' => EventSteps::FINISHED,
            'currentChapter' => 0,
            'peerCheck' => null,
        ], array_map(fn($v) => is_array($v) ? json_encode($v) : $v, $overrides));
    }

    /** Event of 2 chapters where only chapter 1 is assigned. */
    private function event(object $translator, bool $drafted = false): object
    {
        return (object)[
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [(object)[
                'chapter' => 1,
                'trID' => 7,
                'memberID' => $translator->memberID,
                'chunks' => $drafted ? json_encode([[1], [2]]) : null,
                'done' => false,
                'translator' => $translator,
            ]],
            'chunks' => $drafted ? [
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-02 10:00:00'],
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-01 10:00:00'],
            ] : [],
        ];
    }

    private function chapter1(object $translator, bool $drafted = false): array
    {
        return RadioProgress::calculateEventProgress($this->event($translator, $drafted))['chapters'][1];
    }

    private function peer(int $memberID, int $done): array
    {
        return $this->chapter1($this->translator(['peerCheck' => [1 => ['memberID' => $memberID, 'done' => $done]]]), true);
    }

    public function testConsumeInProgress(): void
    {
        $data = RadioProgress::calculateEventProgress(
            $this->event($this->translator(['step' => EventSteps::CONSUME, 'currentChapter' => 1]))
        );
        $ch = $data['chapters'][1];

        $this->assertSame(EventSteps::CONSUME, $ch['step']);
        $this->assertSame(S::IN_PROGRESS, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
        $this->assertSame([], $ch['chunksData']);
        $this->assertSame([], $data['chapters'][2]);
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testChapterNotCurrentAndNotStartedStaysAtPray(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 2]));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(S::NOT_STARTED, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testMultiDraftBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['multiDraft']['state']);
        $this->assertSame(25, $ch['progress']);
    }

    public function testMultiDraftWithTranslatedChunks(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 1]), true);

        $this->assertSame(S::IN_PROGRESS, $ch['multiDraft']['state']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit'], 'latest edit wins regardless of order');
        $this->assertSame(25, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 1]), true);

        $this->assertSame(S::FINISHED, $ch['multiDraft']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertSame(50, $ch['progress']);
    }

    public function testPeerCheckWithoutChecker(): void
    {
        $ch = $this->peer(0, 0);

        $this->assertSame(EventSteps::FINISHED, $ch['step']);
        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::NOT_STARTED, $ch['peerChk']['state']);
        $this->assertArrayNotHasKey('checkerID', $ch);
        $this->assertSame(75, $ch['progress']);
    }

    public function testPeerCheckInProgress(): void
    {
        $ch = $this->peer(30, 0);

        $this->assertSame(S::IN_PROGRESS, $ch['peerChk']['state']);
        $this->assertSame(30, $ch['checkerID']);
        $this->assertSame(75, $ch['progress']);
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->translator(['peerCheck' => [1 => ['memberID' => 30, 'done' => 1]]]), true);

        $data = RadioProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'multiDraft', 'selfEdit', 'peerChk'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertSame(100, $ch['progress']);
        $this->assertSame([10, 30], array_keys($data['members']));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, RadioProgress::calculateEventProgress($event, true));
    }
}
