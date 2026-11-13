<?php

namespace Tests\Unit;

use App\Domain\OdbSunProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;

class OdbSunProgressTest extends TestCase
{
    // Weights are 16.6 per step (17 for the last), so sums are compared with a float delta
    private const DELTA = 0.0001;

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
        return OdbSunProgress::calculateEventProgress($this->event($translator, $drafted))['chapters'][1];
    }

    /** Drafted chapter that has reached theological check with the given check states. */
    private function checked(array $kw, ?array $cr = null): array
    {
        return $this->chapter1($this->translator(['kwCheck' => [1 => $kw], 'crCheck' => $cr ? [1 => $cr] : null]), true);
    }

    // --- Before drafting

    public function testConsumeInProgress(): void
    {
        $data = OdbSunProgress::calculateEventProgress(
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
        $ch = $this->chapter1($this->translator(['step' => EventSteps::REARRANGE, 'currentChapter' => 2]));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testRearrangeInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::REARRANGE, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['rearrange']['state']);
        $this->assertEqualsWithDelta(16.6, $ch['progress'], self::DELTA);
    }

    public function testSymbolDraftBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SYMBOL_DRAFT, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['rearrange']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['symbolDraft']['state']);
        $this->assertEqualsWithDelta(33.2, $ch['progress'], self::DELTA);
    }

    // --- Drafting

    public function testSymbolDraftWithTranslatedChunks(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SYMBOL_DRAFT, 'currentChapter' => 1]), true);

        $this->assertSame(S::IN_PROGRESS, $ch['symbolDraft']['state']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit'], 'latest edit wins regardless of order');
        $this->assertEqualsWithDelta(33.2, $ch['progress'], self::DELTA);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 1]), true);

        $this->assertSame(S::FINISHED, $ch['symbolDraft']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertEqualsWithDelta(49.8, $ch['progress'], self::DELTA);
    }

    // --- Checking

    public function testTheoCheckWaitingForChecker(): void
    {
        $ch = $this->checked(['memberID' => 0, 'done' => 0]);

        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::WAITING, $ch['theoChk']['state']);
        $this->assertSame('na', $ch['theoChk']['checkerID']);
        $this->assertEqualsWithDelta(66.4, $ch['progress'], self::DELTA);
    }

    public function testTheoCheckInProgress(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 0]);

        $this->assertSame(S::IN_PROGRESS, $ch['theoChk']['state']);
        $this->assertSame(30, $ch['theoChk']['checkerID']);
        $this->assertEqualsWithDelta(66.4, $ch['progress'], self::DELTA);
    }

    public function testVerseByVerseWaitingAfterTheoCheck(): void
    {
        $noEntry = $this->checked(['memberID' => 30, 'done' => 1]);
        $noChecker = $this->checked(['memberID' => 30, 'done' => 1], ['memberID' => 0, 'done' => 0]);

        foreach ([$noEntry, $noChecker] as $ch) {
            $this->assertSame(S::FINISHED, $ch['theoChk']['state']);
            $this->assertSame(S::WAITING, $ch['crc']['state']);
            $this->assertSame('na', $ch['crc']['checkerID']);
            $this->assertEqualsWithDelta(83, $ch['progress'], self::DELTA);
        }
    }

    public function testVerseByVerseInProgress(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 1], ['memberID' => 40, 'done' => 0]);

        $this->assertSame(S::IN_PROGRESS, $ch['crc']['state']);
        $this->assertSame(40, $ch['crc']['checkerID']);
        $this->assertEqualsWithDelta(83, $ch['progress'], self::DELTA);
    }

    public function testVerseByVerseIgnoredUntilTheoCheckDone(): void
    {
        $ch = $this->checked(['memberID' => 30, 'done' => 0], ['memberID' => 40, 'done' => 1]);

        $this->assertSame(S::NOT_STARTED, $ch['crc']['state']);
        $this->assertEqualsWithDelta(66.4, $ch['progress'], self::DELTA);
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->translator([
            'kwCheck' => [1 => ['memberID' => 30, 'done' => 1]],
            'crCheck' => [1 => ['memberID' => 40, 'done' => 1]],
        ]), true);

        $data = OdbSunProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'rearrange', 'symbolDraft', 'selfEdit', 'theoChk', 'crc'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertEqualsWithDelta(100, $ch['progress'], self::DELTA);
        $this->assertSame([10, 30, 40], array_keys($data['members']));
        // averaged over every chapter in the book, assigned or not
        $this->assertEqualsWithDelta(50, $data['overall_progress'], self::DELTA);
        $this->assertEqualsWithDelta(50, OdbSunProgress::calculateEventProgress($event, true), self::DELTA);
    }
}
