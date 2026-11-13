<?php

namespace Tests\Unit;

use App\Domain\TnProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TnProgressTest extends TestCase
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
     * Book of 2 chapters plus the intro (chapter 0); only chapter 1 is assigned, split into 2 chunks.
     * $drafted is how many chunks have a translation (0 = drafting not started).
     */
    private function event(object $translator, int $drafted = 0): object
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
            'chunks' => array_map(
                fn($i) => (object)['chapter' => 1, 'dateUpdate' => "2024-01-0{$i} 10:00:00", 'translatedVerses' => null],
                $drafted ? range(1, $drafted) : []
            ),
        ];
    }

    private function chapter1(object $translator, int $drafted = 0): array
    {
        return TnProgress::calculateEventProgress($this->event($translator, $drafted))['chapters'][1];
    }

    /** Fully drafted chapter in the checking stage. */
    private function checking(int $done, ?array $peer = null, int $checker = 30): array
    {
        return $this->chapter1($this->translator([
            'otherCheck' => [1 => ['memberID' => $checker, 'done' => $done]],
            'peerCheck' => $peer ? [1 => $peer] : null,
        ]), 2);
    }

    // --- Before drafting

    public function testConsumeInProgress(): void
    {
        $data = TnProgress::calculateEventProgress(
            $this->event($this->translator(['step' => EventSteps::CONSUME, 'currentChapter' => 1]))
        );
        $ch = $data['chapters'][1];

        $this->assertSame(EventSteps::CONSUME, $ch['step']);
        $this->assertSame(S::IN_PROGRESS, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
        $this->assertSame([], $ch['chunksData']);
        $this->assertSame([], $data['chapters'][0], 'intro chapter is part of the book');
        $this->assertSame([], $data['chapters'][2]);
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testChapterNotCurrentAndNotStartedStaysAtPray(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::CONSUME, 'currentChapter' => 2]));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testReadChunkBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::READ_CHUNK, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['blindDraft']['state']);
        $this->assertSame(17, $ch['progress']);
    }

    // --- Drafting

    public function testTranslatedChunksAddTheirShareOf17Percent(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::BLIND_DRAFT, 'currentChapter' => 1]), 1);

        $this->assertSame(EventSteps::BLIND_DRAFT, $ch['step']);
        $this->assertSame(S::IN_PROGRESS, $ch['blindDraft']['state']);
        $this->assertSame('2024-01-01 10:00:00', $ch['lastEdit']);
        // half the chunks (8.5) + consume (17)
        $this->assertEquals(25.5, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 1]), 2);

        $this->assertSame(S::FINISHED, $ch['blindDraft']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertSame(34, $ch['progress']);
    }

    // --- Checking: otherCheck "done" counts the checker's finished steps

    public function testCheckWaitingForChecker(): void
    {
        $ch = $this->checking(0, null, 0);

        $this->assertSame(EventSteps::FINISHED, $ch['step']);
        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::WAITING, $ch['consumeChk']['state']);
        $this->assertArrayNotHasKey('checkerID', $ch);
        $this->assertSame(51, $ch['progress']);
    }

    public static function checkerStages(): array
    {
        $ns = S::NOT_STARTED;
        $ip = S::IN_PROGRESS;
        $f = S::FINISHED;

        //            done  consumeChk highlightChk selfEditChk kwc  progress
        return [
            'consume' => [1, $ip, $ns, $ns, $ns, 51],
            'highlight' => [2, $f, $ip, $ns, $ns, 61],
            'self edit' => [3, $f, $f, $ip, $ns, 71],
            'keyword check' => [4, $f, $f, $f, $ip, 71],
        ];
    }

    #[DataProvider('checkerStages')]
    public function testCheckerStages(int $done, string $consume, string $highlight, string $selfEdit, string $kwc, int $progress): void
    {
        $ch = $this->checking($done);

        $this->assertSame(30, $ch['checkerID']);
        $this->assertSame(
            [$consume, $highlight, $selfEdit, $kwc, S::NOT_STARTED],
            [$ch['consumeChk']['state'], $ch['highlightChk']['state'], $ch['selfEditChk']['state'], $ch['kwc']['state'], $ch['peerChk']['state']]
        );
        $this->assertSame($progress, $ch['progress']);
    }

    public function testPeerCheckWaiting(): void
    {
        $noEntry = $this->checking(5);
        $noChecker = $this->checking(5, ['memberID' => 0, 'done' => 0]);

        foreach ([$noEntry, $noChecker] as $ch) {
            $this->assertSame(S::FINISHED, $ch['kwc']['state']);
            $this->assertSame(S::WAITING, $ch['peerChk']['state']);
            $this->assertSame('na', $ch['peerChk']['checkerID']);
            $this->assertSame(81, $ch['progress']);
        }
    }

    public function testPeerCheckInProgress(): void
    {
        $data = TnProgress::calculateEventProgress($this->event($this->translator([
            'otherCheck' => [1 => ['memberID' => 30, 'done' => 5]],
            'peerCheck' => [1 => ['memberID' => 40, 'done' => 0]],
        ]), 2));
        $ch = $data['chapters'][1];

        $this->assertSame(S::IN_PROGRESS, $ch['peerChk']['state']);
        $this->assertSame(40, $ch['peerChk']['checkerID']);
        $this->assertSame(81, $ch['progress']);
        $this->assertSame([10, 30, 40], array_keys($data['members']));
    }

    public function testPeerCheckChecked(): void
    {
        $ch = $this->checking(5, ['memberID' => 40, 'done' => 1]);

        $this->assertSame(S::CHECKED, $ch['peerChk']['state']);
        $this->assertSame(86, $ch['progress']);
    }

    public function testFullyCheckedChapter(): void
    {
        $event = $this->event($this->translator([
            'otherCheck' => [1 => ['memberID' => 30, 'done' => 6]],
            'peerCheck' => [1 => ['memberID' => 40, 'done' => 1]],
        ]), 2);

        $data = TnProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'blindDraft', 'selfEdit', 'consumeChk', 'highlightChk', 'selfEditChk', 'kwc', 'peerChk'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertSame(EventSteps::FINISHED, $ch['stepChk']);
        $this->assertSame(40, $ch['peerChk']['checkerID']);
        // 90 + up to 10 for chunks the checker has edited (none here)
        $this->assertSame(90, $ch['progress']);
        // averaged over the intro and both chapters
        $this->assertEquals(30, $data['overall_progress']);
        $this->assertEquals(30, TnProgress::calculateEventProgress($event, true));
    }

    // --- Chunks the checker has edited add their share of 10%

    /** Gives the first $count chunks checker verses; the rest keep the empty checker entry new chunks start with. */
    private function withCheckerVerses(object $event, int $count): object
    {
        foreach ($event->chunks as $i => $chunk) {
            $chunk->translatedVerses = json_encode([
                'translator' => ['verses' => 'Translator note'],
                'checker' => ['verses' => $i < $count ? 'Checked note' : ''],
            ]);
        }
        return $event;
    }

    public function testCheckerEditedChunksAddTheirShareOf10Percent(): void
    {
        $event = $this->withCheckerVerses($this->event($this->translator([
            'otherCheck' => [1 => ['memberID' => 30, 'done' => 3]],
        ]), 2), 1);

        $ch = TnProgress::calculateEventProgress($event)['chapters'][1];

        $this->assertSame(S::IN_PROGRESS, $ch['selfEditChk']['state']);
        $this->assertSame(76, $ch['progress']);
    }

    public function testFullyCheckedChapterWithCheckerEditsIs100(): void
    {
        $event = $this->withCheckerVerses($this->event($this->translator([
            'otherCheck' => [1 => ['memberID' => 30, 'done' => 6]],
            'peerCheck' => [1 => ['memberID' => 40, 'done' => 1]],
        ]), 2), 2);

        $this->assertSame(100, TnProgress::calculateEventProgress($event)['chapters'][1]['progress']);
    }
}
