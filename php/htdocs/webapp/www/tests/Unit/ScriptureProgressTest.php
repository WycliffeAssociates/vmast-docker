<?php

namespace Tests\Unit;

use App\Domain\ScriptureProgress;
use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;

class ScriptureProgressTest extends TestCase
{
    private function translator(array $overrides = []): object
    {
        return (object)array_merge([
            'memberID' => 10,
            'step' => EventSteps::FINISHED,
            'currentChapter' => 0,
            'verbCheck' => null,
            'peerCheck' => null,
            'kwCheck' => null,
            'crCheck' => null,
            'otherCheck' => null,
        ], array_map(fn($v) => is_array($v) ? json_encode($v) : $v, $overrides));
    }

    /**
     * Event of 2 chapters where only chapter 1 is assigned.
     * $chunks null means chunking is not done; $drafted is how many chunks have a translation.
     */
    private function event(object $translator, ?array $chunks = null, int $drafted = 0, string $mode = InputMode::NORMAL): object
    {
        return (object)[
            'inputMode' => $mode,
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [(object)[
                'chapter' => 1,
                'trID' => 7,
                'memberID' => $translator->memberID,
                'chunks' => $chunks ? json_encode($chunks) : null,
                'done' => false,
                'translator' => $translator,
            ]],
            'chunks' => array_map(
                fn($i) => (object)['chapter' => 1, 'dateUpdate' => "2024-01-0{$i} 10:00:00"],
                $drafted ? range(1, $drafted) : []
            ),
        ];
    }

    private function chapter1(object $event): array
    {
        return ScriptureProgress::calculateEventProgress($event)['chapters'][1];
    }

    private function finishedChecks(): array
    {
        return [
            'verbCheck' => [1 => ['memberID' => 20, 'done' => 1]],
            'peerCheck' => [1 => ['memberID' => 30, 'done' => 2]],
            'kwCheck' => [1 => ['memberID' => 40, 'done' => 2]],
            'crCheck' => [1 => ['memberID' => 50, 'done' => 2, 'memberID2' => 60, 'done2' => 1]],
            'otherCheck' => [1 => ['memberID' => 70, 'done' => 1]],
        ];
    }

    // --- Before chunking: progress comes from the step the translator is on

    public function testConsumeInProgress(): void
    {
        $data = ScriptureProgress::calculateEventProgress(
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
        $ch = $this->chapter1($this->event($this->translator(['step' => EventSteps::CONSUME, 'currentChapter' => 2])));

        $this->assertSame(EventSteps::PRAY, $ch['step']);
        $this->assertSame(S::NOT_STARTED, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testVerbalizeWaitingForChecker(): void
    {
        $ch = $this->chapter1($this->event($this->translator(['step' => EventSteps::VERBALIZE, 'currentChapter' => 1])));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::WAITING, $ch['verb']['state']);
        $this->assertSame('na', $ch['verb']['checkerID']);
        $this->assertSame(11, $ch['progress']);
    }

    public function testVerbalizeAssignedButNotChecked(): void
    {
        $ch = $this->chapter1($this->event($this->translator([
            'step' => EventSteps::VERBALIZE,
            'currentChapter' => 1,
            'verbCheck' => [1 => ['memberID' => 20, 'done' => 0]],
        ])));

        $this->assertSame(S::IN_PROGRESS, $ch['verb']['state']);
        $this->assertSame(11, $ch['progress']);
    }

    public function testVerbalizeCheckedByMember(): void
    {
        $data = ScriptureProgress::calculateEventProgress($this->event($this->translator([
            'step' => EventSteps::VERBALIZE,
            'currentChapter' => 1,
            'verbCheck' => [1 => ['memberID' => 20, 'done' => 1]],
        ])));

        $this->assertSame(S::CHECKED, $data['chapters'][1]['verb']['state']);
        $this->assertSame(20, $data['chapters'][1]['verb']['checkerID']);
        $this->assertSame(17, $data['chapters'][1]['progress']);
        $this->assertSame([10 => '', 20 => ''], $data['members']);
    }

    public function testVerbalizeCheckedByNamedOutsider(): void
    {
        $data = ScriptureProgress::calculateEventProgress($this->event($this->translator([
            'step' => EventSteps::VERBALIZE,
            'currentChapter' => 1,
            'verbCheck' => [1 => ['memberID' => 'Pastor John', 'done' => 1]],
        ])));

        $checkerID = $data['chapters'][1]['verb']['checkerID'];
        $this->assertStringStartsWith('chk', $checkerID);
        $this->assertSame('Pastor John', $data['members'][$checkerID]);
    }

    public function testVerbalizeCheckerDoesNotLeakToNextChapter(): void
    {
        $checked = $this->translator([
            'step' => EventSteps::VERBALIZE,
            'currentChapter' => 1,
            'verbCheck' => [1 => ['memberID' => 20, 'done' => 1]],
        ]);
        $other = $this->translator(['memberID' => 11, 'step' => EventSteps::VERBALIZE, 'currentChapter' => 2]);
        $event = $this->event($checked);
        $event->chapters[] = (object)[
            'chapter' => 2, 'trID' => 8, 'memberID' => 11, 'chunks' => null, 'done' => false, 'translator' => $other,
        ];

        $data = ScriptureProgress::calculateEventProgress($event);

        $this->assertSame(20, $data['chapters'][1]['verb']['checkerID']);
        $this->assertSame(S::WAITING, $data['chapters'][2]['verb']['state']);
        $this->assertSame('na', $data['chapters'][2]['verb']['checkerID']);
    }

    public function testChunkingInProgress(): void
    {
        $ch = $this->chapter1($this->event($this->translator(['step' => EventSteps::CHUNKING, 'currentChapter' => 1])));

        $this->assertSame(S::FINISHED, $ch['verb']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['chunking']['state']);
        $this->assertSame(22, $ch['progress']);
    }

    public function testBlindDraftBeforeAnyChunkTranslated(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator(['step' => EventSteps::BLIND_DRAFT, 'currentChapter' => 1]),
            [[1, 2], [3, 4]]
        ));

        $this->assertSame(S::FINISHED, $ch['chunking']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['blindDraft']['state']);
        $this->assertSame(33, $ch['progress']);
    }

    // --- After drafting starts

    public function testTranslatedChunksAddTheirShareOf11Percent(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator([
                'step' => EventSteps::BLIND_DRAFT,
                'currentChapter' => 1,
                'verbCheck' => [1 => ['memberID' => 20, 'done' => 1]],
            ]),
            [[1, 2], [3, 4]],
            1
        ));

        $this->assertSame(S::IN_PROGRESS, $ch['blindDraft']['state']);
        $this->assertSame(S::NOT_STARTED, $ch['selfEdit']['state']);
        $this->assertSame('2024-01-01 10:00:00', $ch['lastEdit']);
        // half the chunks (5.5) + consume, verbalize, chunking (33)
        $this->assertEquals(38.5, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator(['step' => EventSteps::SELF_CHECK, 'currentChapter' => 1]),
            [[1, 2]],
            1
        ));

        $this->assertSame(EventSteps::SELF_CHECK, $ch['step']);
        $this->assertSame(S::FINISHED, $ch['blindDraft']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
    }

    public function testChecksWaitingForCheckers(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator([
                'peerCheck' => [1 => ['memberID' => 0, 'done' => 0]],
                'kwCheck' => [1 => ['memberID' => 0, 'done' => 0]],
                'crCheck' => [1 => ['memberID' => 0, 'done' => 0]],
                'otherCheck' => [1 => ['memberID' => 0, 'done' => 0]],
            ]),
            [[1, 2]],
            1
        ));

        $this->assertSame(EventSteps::FINISHED, $ch['step']);
        $this->assertSame([S::WAITING, 0], [$ch['peer']['state'], $ch['peer']['checkerID']]);
        $this->assertSame([S::WAITING, 0], [$ch['kwc']['state'], $ch['kwc']['checkerID']]);
        $this->assertSame([S::WAITING, 0], [$ch['crc']['state'], $ch['crc']['checkerID']]);
        $this->assertSame(S::IN_PROGRESS, $ch['finalReview']['state']);
    }

    public function testChecksCheckedAddPartialProgress(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator([
                'peerCheck' => [1 => ['memberID' => 30, 'done' => 1]],
                'kwCheck' => [1 => ['memberID' => 40, 'done' => 1]],
                'crCheck' => [1 => ['memberID' => 50, 'done' => 1]],
            ]),
            [[1, 2]],
            1
        ));

        $this->assertSame(S::CHECKED, $ch['peer']['state']);
        $this->assertSame(S::CHECKED, $ch['kwc']['state']);
        $this->assertSame(S::CHECKED, $ch['crc']['state']);
        // chunks, consume, chunking, self edit (44) + three partial checks (18)
        $this->assertSame(62, $ch['progress']);
    }

    public function testVerseByVerseNeedsBothCheckersToBeInProgress(): void
    {
        $oneChecker = $this->chapter1($this->event(
            $this->translator(['crCheck' => [1 => ['memberID' => 50, 'done' => 0]]]), [[1]], 1
        ));
        $twoCheckers = $this->chapter1($this->event(
            $this->translator(['crCheck' => [1 => ['memberID' => 50, 'done' => 0, 'memberID2' => 60, 'done2' => 0]]]), [[1]], 1
        ));

        $this->assertSame(S::WAITING, $oneChecker['crc']['state']);
        $this->assertSame(50, $oneChecker['crc']['checkerID']);
        $this->assertSame(S::IN_PROGRESS, $twoCheckers['crc']['state']);
        $this->assertSame(S::IN_PROGRESS, $twoCheckers['crc']['state2']);
        $this->assertSame(60, $twoCheckers['crc']['checkerID2']);
    }

    public function testFullyCheckedChapterIs100(): void
    {
        $event = $this->event($this->translator($this->finishedChecks()), [[1, 2], [3]], 2);

        $data = ScriptureProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'verb', 'chunking', 'blindDraft', 'selfEdit', 'peer', 'kwc', 'crc', 'finalReview'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertSame(S::CHECKED, $ch['crc']['state2']);
        $this->assertEquals(100, $ch['progress']);
        $this->assertSame([10, 20, 30, 40, 50, 60], array_keys($data['members']));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, ScriptureProgress::calculateEventProgress($event, true));
    }

    // --- Other input modes ignore the normal-mode steps

    public function testSpeechToTextFullyChecked(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator(['peerCheck' => [1 => ['memberID' => 30, 'done' => 2]]]),
            [[1]], 1, InputMode::SPEECH_TO_TEXT
        ));

        $this->assertSame(100, $ch['progress']);
    }

    public function testSpeechToTextPeerChecked(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator(['peerCheck' => [1 => ['memberID' => 30, 'done' => 1]]]),
            [[1]], 1, InputMode::SPEECH_TO_TEXT
        ));

        $this->assertSame(83, $ch['progress']);
    }

    public function testScriptureInputDraftedAndSelfEdited(): void
    {
        $ch = $this->chapter1($this->event($this->translator(), [[1]], 1, InputMode::SCRIPTURE_INPUT));

        $this->assertSame(100, $ch['progress']);
    }

    public function testScriptureInputBeforeChunkingHasNoProgress(): void
    {
        $ch = $this->chapter1($this->event(
            $this->translator(['step' => EventSteps::CHUNKING, 'currentChapter' => 1]),
            null, 0, InputMode::SCRIPTURE_INPUT
        ));

        $this->assertSame(0, $ch['progress']);
    }
}
