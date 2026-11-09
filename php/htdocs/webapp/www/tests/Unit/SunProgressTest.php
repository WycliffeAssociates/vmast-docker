<?php

namespace Tests\Unit;

use App\Domain\SunProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;

class SunProgressTest extends TestCase
{
    private function translator(array $overrides = []): object
    {
        return (object)array_merge([
            'memberID' => 10,
            'step' => EventSteps::FINISHED,
            'currentChapter' => 0,
            'kwCheck' => null,
            'crCheck' => null,
        ], array_map(fn($v) => is_array($v) ? json_encode($v) : $v, $overrides));
    }

    /**
     * Event of 2 chapters where only chapter 1 is assigned.
     * $drafted is how many of chapter 1's chunks have a translation (0 = drafting not started).
     */
    private function event(object $translator, int $drafted = 0): object
    {
        return (object)[
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [(object)[
                'chapter' => 1,
                'trID' => 7,
                'memberID' => $translator->memberID,
                'chunks' => $drafted ? json_encode([[1, 2], [3]]) : null,
                'done' => false,
                'translator' => $translator,
            ]],
            'chunks' => array_map(
                fn($i) => (object)['chapter' => 1, 'dateUpdate' => "2024-01-0{$i} 10:00:00"],
                $drafted ? range(1, $drafted) : []
            ),
        ];
    }

    private function chapter1(object $translator, int $drafted = 0): array
    {
        return SunProgress::calculateEventProgress($this->event($translator, $drafted))['chapters'][1];
    }

    /** Drafted chapter that has reached theological check with the given check states. */
    private function checked(array $kw, ?array $cr = null): array
    {
        return $this->chapter1($this->translator(['kwCheck' => [1 => $kw], 'crCheck' => $cr ? [1 => $cr] : null]), 2);
    }

    // --- Before drafting: progress comes from the step the translator is on

    public function testConsumeInProgress(): void
    {
        $data = SunProgress::calculateEventProgress(
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
        $ch = $this->chapter1($this->translator(['step' => EventSteps::CHUNKING, 'currentChapter' => 2]));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(S::NOT_STARTED, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testChunkingInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::CHUNKING, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['chunking']['state']);
        $this->assertEquals(12.5, $ch['progress']);
    }

    public function testRearrangeInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::REARRANGE, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['chunking']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['rearrange']['state']);
        $this->assertEquals(25, $ch['progress']);
    }

    public function testSymbolDraftBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SYMBOL_DRAFT, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['rearrange']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['symbolDraft']['state']);
        $this->assertEquals(37.5, $ch['progress']);
    }

    // --- Drafting

    public function testSymbolDraftWithTranslatedChunks(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SYMBOL_DRAFT, 'currentChapter' => 1]), 2);

        $this->assertSame(S::IN_PROGRESS, $ch['symbolDraft']['state']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit']);
        $this->assertCount(2, $ch['chunksData']);
        $this->assertEquals(37.5, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 1]), 1);

        $this->assertSame(S::FINISHED, $ch['symbolDraft']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertEquals(50, $ch['progress']);
    }

    // --- Checking

    public function testTheoCheckWaitingForChecker(): void
    {
        $ch = $this->checked(['memberID' => 0, 'done' => 0]);

        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::WAITING, $ch['theoChk']['state']);
        $this->assertSame('na', $ch['theoChk']['checkerID']);
        $this->assertSame(S::NOT_STARTED, $ch['crc']['state']);
        $this->assertEquals(62.5, $ch['progress']);
    }

    public function testTheoCheckInProgress(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 0]);

        $this->assertSame(S::IN_PROGRESS, $ch['theoChk']['state']);
        $this->assertSame(30, $ch['theoChk']['checkerID']);
        $this->assertEquals(62.5, $ch['progress']);
    }

    public function testVerseByVerseWaitingAfterTheoCheck(): void
    {
        $noEntry = $this->checked(['memberID' => 30, 'done' => 1]);
        $noChecker = $this->checked(['memberID' => 30, 'done' => 1], ['memberID' => 0, 'done' => 0]);

        foreach ([$noEntry, $noChecker] as $ch) {
            $this->assertSame(S::FINISHED, $ch['theoChk']['state']);
            $this->assertSame(S::WAITING, $ch['crc']['state']);
            $this->assertSame('na', $ch['crc']['checkerID']);
            $this->assertEquals(75, $ch['progress']);
        }
    }

    public function testVerseByVerseInProgress(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 1], ['memberID' => 40, 'done' => 0]);

        $this->assertSame(S::IN_PROGRESS, $ch['crc']['state']);
        $this->assertSame(40, $ch['crc']['checkerID']);
        $this->assertSame(S::NOT_STARTED, $ch['finalReview']['state']);
        $this->assertEquals(75, $ch['progress']);
    }

    public function testFinalReviewInProgress(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 1], ['memberID' => 40, 'done' => 1]);

        $this->assertSame(S::FINISHED, $ch['crc']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['finalReview']['state']);
        $this->assertEquals(87.5, $ch['progress']);
    }

    public function testVerseByVerseIgnoredUntilTheoCheckDone(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 0], ['memberID' => 40, 'done' => 2]);

        $this->assertSame(S::NOT_STARTED, $ch['crc']['state']);
        $this->assertSame(S::NOT_STARTED, $ch['finalReview']['state']);
        $this->assertEquals(62.5, $ch['progress']);
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->translator([
            'kwCheck' => [1 => ['memberID' => 30, 'done' => 1]],
            'crCheck' => [1 => ['memberID' => 40, 'done' => 2]],
        ]), 2);

        $data = SunProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'chunking', 'rearrange', 'symbolDraft', 'selfEdit', 'theoChk', 'crc', 'finalReview'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertEquals(100, $ch['progress']);
        $this->assertSame([10, 30, 40], array_keys($data['members']));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, SunProgress::calculateEventProgress($event, true));
    }
}
