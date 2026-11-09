<?php

namespace Tests\Unit;

use App\Domain\ResourceProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;
use Support\Collection;

/**
 * OBS: consume, draft, self edit, keyword check and peer check are 20% each.
 * BC/BCA: no consume step, so draft, self edit, keyword check and peer check are 25% each.
 */
class ResourceProgressTest extends TestCase
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
     * Book of 2 chapters (BCA: 3 words) where only chapter 1 is assigned, split into 2 chunks.
     * $drafted is how many chunks have a translation (0 = drafting not started).
     */
    private function event(object $translator, int $drafted = 0, string $bookProject = 'obs'): object
    {
        return (object)[
            'project' => (object)['bookProject' => $bookProject],
            'bookInfo' => (object)['chaptersNum' => 2],
            'words' => new Collection(['a', 'b', 'c']),
            'chapters' => [(object)[
                'chapter' => 1,
                'trID' => 7,
                'memberID' => $translator->memberID,
                'chunks' => $drafted ? json_encode([[1], [2]]) : null,
                'done' => false,
                'translator' => $translator,
            ]],
            'chunks' => array_map(
                fn($i) => (object)['chapter' => 1, 'dateUpdate' => "2024-01-0{$i} 10:00:00"],
                $drafted ? range(1, $drafted) : []
            ),
        ];
    }

    private function chapter1(object $translator, int $drafted = 0, string $bookProject = 'obs'): array
    {
        return ResourceProgress::calculateEventProgress($this->event($translator, $drafted, $bookProject))['chapters'][1];
    }

    /** Fully drafted chapter in the checking stage. */
    private function checking(int $done, ?array $peer = null, int $checker = 30, string $bookProject = 'obs'): array
    {
        return $this->chapter1($this->translator([
            'otherCheck' => [1 => ['memberID' => $checker, 'done' => $done]],
            'peerCheck' => $peer ? [1 => $peer] : null,
        ]), 2, $bookProject);
    }

    // --- Chapters per resource type

    public function testChaptersPerResourceType(): void
    {
        $chapters = fn($type) => array_keys(
            ResourceProgress::calculateEventProgress($this->event($this->translator(), 0, $type))['chapters']
        );

        $this->assertSame([1, 2], $chapters('obs'));
        $this->assertSame([0, 1, 2], $chapters('bc'), 'BC has a book intro as chapter 0');
        $this->assertSame([1, 2, 3], $chapters('bca'), 'BCA has one chapter per word');
    }

    // --- OBS before drafting

    public function testConsumeInProgress(): void
    {
        $data = ResourceProgress::calculateEventProgress(
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
        $ch = $this->chapter1($this->translator(['step' => EventSteps::CONSUME, 'currentChapter' => 2]));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testBlindDraftBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::BLIND_DRAFT, 'currentChapter' => 1]));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['blindDraft']['state']);
        $this->assertSame(20, $ch['progress']);
    }

    // --- OBS drafting and checking

    public function testTranslatedChunksAddTheirShareOfTheDraftStep(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::BLIND_DRAFT, 'currentChapter' => 1]), 1);

        $this->assertSame(EventSteps::BLIND_DRAFT, $ch['step']);
        $this->assertSame(S::IN_PROGRESS, $ch['blindDraft']['state']);
        $this->assertSame('2024-01-01 10:00:00', $ch['lastEdit']);
        // half the chunks (10) + consume (20)
        $this->assertSame(30, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 1]), 2);

        $this->assertSame(S::FINISHED, $ch['blindDraft']['state']);
        $this->assertSame(S::FINISHED, $ch['multiDraft']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertSame(40, $ch['progress']);
    }

    public function testKeywordCheckWaitingForChecker(): void
    {
        $ch = $this->checking(0, null, 0);

        $this->assertSame(EventSteps::FINISHED, $ch['step']);
        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::WAITING, $ch['kwc']['state']);
        $this->assertArrayNotHasKey('checkerID', $ch);
        $this->assertSame(60, $ch['progress']);
    }

    public function testKeywordCheckInProgress(): void
    {
        $ch = $this->checking(1);

        $this->assertSame(S::IN_PROGRESS, $ch['kwc']['state']);
        $this->assertSame(30, $ch['checkerID']);
        $this->assertSame(60, $ch['progress']);
    }

    public function testPeerCheckWaiting(): void
    {
        foreach ([$this->checking(2), $this->checking(2, ['memberID' => 0, 'done' => 0])] as $ch) {
            $this->assertSame(S::FINISHED, $ch['kwc']['state']);
            $this->assertSame(S::WAITING, $ch['peerChk']['state']);
            $this->assertSame('na', $ch['peerChk']['checkerID']);
            $this->assertSame(80, $ch['progress']);
        }
    }

    public function testPeerCheckInProgress(): void
    {
        $ch = $this->checking(2, ['memberID' => 40, 'done' => 0]);

        $this->assertSame(S::IN_PROGRESS, $ch['peerChk']['state']);
        $this->assertSame(40, $ch['peerChk']['checkerID']);
        $this->assertSame(80, $ch['progress']);
    }

    public function testPeerCheckChecked(): void
    {
        $ch = $this->checking(2, ['memberID' => 40, 'done' => 1]);

        $this->assertSame(S::CHECKED, $ch['peerChk']['state']);
        $this->assertSame(90, $ch['progress']);
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->translator([
            'otherCheck' => [1 => ['memberID' => 30, 'done' => 3]],
            'peerCheck' => [1 => ['memberID' => 40, 'done' => 1]],
        ]), 2);

        $data = ResourceProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'blindDraft', 'multiDraft', 'selfEdit', 'kwc', 'peerChk'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertSame(EventSteps::FINISHED, $ch['stepChk']);
        $this->assertSame(100, $ch['progress']);
        $this->assertSame([10, 30, 40], array_keys($data['members']));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, ResourceProgress::calculateEventProgress($event, true));
    }

    // --- BC/BCA: multi draft only, 25% steps

    public function testBcMultiDraftBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 1]), 0, 'bc');

        $this->assertSame(S::IN_PROGRESS, $ch['multiDraft']['state']);
        $this->assertSame(S::NOT_STARTED, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testBcDraftedChunksCountWithoutConsume(): void
    {
        $ch = $this->chapter1($this->translator(['step' => EventSteps::MULTI_DRAFT, 'currentChapter' => 1]), 1, 'bc');

        $this->assertSame(S::IN_PROGRESS, $ch['multiDraft']['state']);
        $this->assertEquals(12.5, $ch['progress']);
    }

    public function testBcaPeerCheckedUsesHalfStep(): void
    {
        $ch = $this->checking(2, ['memberID' => 40, 'done' => 1], 30, 'bca');

        $this->assertSame(88, $ch['progress']);
    }

    public function testBcFullyCheckedChapterIs100AndOverallIncludesIntro(): void
    {
        $event = $this->event($this->translator([
            'otherCheck' => [1 => ['memberID' => 30, 'done' => 3]],
            'peerCheck' => [1 => ['memberID' => 40, 'done' => 1]],
        ]), 2, 'bc');

        $data = ResourceProgress::calculateEventProgress($event);

        $this->assertSame(100, $data['chapters'][1]['progress']);
        $this->assertEqualsWithDelta(100 / 3, $data['overall_progress'], 0.0001);
    }
}
