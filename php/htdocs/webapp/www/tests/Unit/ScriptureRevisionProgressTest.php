<?php

namespace Tests\Unit;

use App\Domain\ScriptureRevisionProgress;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\RevisionMode;
use Helpers\Constants\StepsStates as S;
use PHPUnit\Framework\TestCase;
use Support\Collection;

/**
 * Major revision: consume, self edit, peer, keyword and verse-by-verse checks are 20% each (10% when checked).
 * Minor revision has no peer check, so the other four are 25% each (12.5% when checked).
 */
class ScriptureRevisionProgressTest extends TestCase
{
    /** L2 checker 10 on $step for $currentChapter, with chapter 1's check entries. */
    private function checker(string $step = EventCheckSteps::FINISHED, int $currentChapter = 0, array $checks = []): object
    {
        $json = fn($name) => isset($checks[$name]) ? json_encode([1 => $checks[$name]]) : null;

        return (object)[
            'currentChapter' => $currentChapter,
            'step' => $step,
            'peerCheck' => $json('peer'),
            'kwCheck' => $json('kw'),
            'crCheck' => $json('cr'),
        ];
    }

    /** Book of 2 chapters; chapter 1 is assigned to L2 checker 10, chapter 2 is not assigned. */
    private function event(object $checker, string $mode = RevisionMode::MAJOR): object
    {
        $chapter = fn($n, $l2memberID, $checkerL2) => (object)[
            'chapter' => $n,
            'l2chID' => $n,
            'l2memberID' => $l2memberID,
            'l2checked' => false,
            'checkerL2' => $checkerL2,
        ];

        return (object)[
            'revisionMode' => $mode,
            'bookInfo' => (object)['chaptersNum' => 2],
            'chapters' => [$chapter(1, 10, $checker), $chapter(2, 0, null)],
            'chunks' => new Collection([
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-02 10:00:00'],
                (object)['chapter' => 1, 'dateUpdate' => '2024-01-01 10:00:00'],
            ]),
        ];
    }

    private function chapter1(object $checker, string $mode = RevisionMode::MAJOR): array
    {
        return ScriptureRevisionProgress::calculateEventProgress($this->event($checker, $mode))['chapters'][1];
    }

    /** Chapter past self edit with the given check entries. */
    private function checks(array $checks, string $mode = RevisionMode::MAJOR): array
    {
        return $this->chapter1($this->checker(EventCheckSteps::FINISHED, 0, $checks), $mode);
    }

    private const PEER_DONE = ['memberID' => 20, 'done' => 2];
    private const KW_DONE = ['memberID' => 30, 'done' => 2];

    // --- Before checking

    public function testNotStarted(): void
    {
        $data = ScriptureRevisionProgress::calculateEventProgress($this->event($this->checker(EventCheckSteps::PRAY, 1)));
        $ch = $data['chapters'][1];

        foreach (['consume', 'selfEdit', 'peer', 'kwc', 'crc'] as $step) {
            $this->assertSame(S::NOT_STARTED, $ch[$step]['state'], $step);
        }
        $this->assertSame(0, $ch['progress']);
        $this->assertSame('2024-01-02 10:00:00', $ch['lastEdit'], 'latest edit wins regardless of order');
        $this->assertArrayNotHasKey('progress', $data['chapters'][2], 'unassigned chapter');
        $this->assertSame([10 => ''], $data['members']);
    }

    public function testConsumeInProgress(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::CONSUME, 1));

        $this->assertSame(S::IN_PROGRESS, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
    }

    public function testSelfCheckInProgress(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::SELF_CHECK, 1));

        $this->assertSame(S::FINISHED, $ch['consume']['state']);
        $this->assertSame(S::IN_PROGRESS, $ch['selfEdit']['state']);
        $this->assertSame(20, $ch['progress']);
    }

    public function testStepOfAnotherChapterIsIgnored(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::SELF_CHECK, 2));

        $this->assertSame(S::NOT_STARTED, $ch['consume']['state']);
        $this->assertSame(0, $ch['progress']);
    }

    // --- Major revision checks

    public function testPeerCheckStages(): void
    {
        $waiting = $this->checks(['peer' => ['memberID' => 0, 'done' => 0]]);
        $this->assertSame([S::WAITING, 0, 40], [$waiting['peer']['state'], $waiting['peer']['checkerID'], $waiting['progress']]);

        $inProgress = $this->checks(['peer' => ['memberID' => 20, 'done' => 0]]);
        $this->assertSame([S::IN_PROGRESS, 20, 40], [$inProgress['peer']['state'], $inProgress['peer']['checkerID'], $inProgress['progress']]);

        $checked = $this->checks(['peer' => ['memberID' => 20, 'done' => 1]]);
        $this->assertSame([S::CHECKED, 50], [$checked['peer']['state'], $checked['progress']]);

        $finished = $this->checks(['peer' => self::PEER_DONE]);
        $this->assertSame([S::FINISHED, 60], [$finished['peer']['state'], $finished['progress']]);
    }

    public function testKeywordCheckStages(): void
    {
        $waiting = $this->checks(['peer' => self::PEER_DONE, 'kw' => ['memberID' => 0, 'done' => 0]]);
        $this->assertSame([S::WAITING, 0, 60], [$waiting['kwc']['state'], $waiting['kwc']['checkerID'], $waiting['progress']]);

        $inProgress = $this->checks(['peer' => self::PEER_DONE, 'kw' => ['memberID' => 30, 'done' => 0]]);
        $this->assertSame([S::IN_PROGRESS, 30, 60], [$inProgress['kwc']['state'], $inProgress['kwc']['checkerID'], $inProgress['progress']]);

        $checked = $this->checks(['peer' => self::PEER_DONE, 'kw' => ['memberID' => 30, 'done' => 1]]);
        $this->assertSame([S::CHECKED, 70], [$checked['kwc']['state'], $checked['progress']]);

        $finished = $this->checks(['peer' => self::PEER_DONE, 'kw' => self::KW_DONE]);
        $this->assertSame([S::FINISHED, 80], [$finished['kwc']['state'], $finished['progress']]);
    }

    public function testVerseByVerseCheckStages(): void
    {
        $done = ['peer' => self::PEER_DONE, 'kw' => self::KW_DONE];

        $waiting = $this->checks($done + ['cr' => ['memberID' => 0, 'done' => 0]]);
        $this->assertSame([S::WAITING, 0, 80], [$waiting['crc']['state'], $waiting['crc']['checkerID'], $waiting['progress']]);

        $inProgress = $this->checks($done + ['cr' => ['memberID' => 40, 'done' => 0]]);
        $this->assertSame([S::IN_PROGRESS, 40, 80], [$inProgress['crc']['state'], $inProgress['crc']['checkerID'], $inProgress['progress']]);

        $checked = $this->checks($done + ['cr' => ['memberID' => 40, 'done' => 1]]);
        $this->assertSame([S::CHECKED, 90], [$checked['crc']['state'], $checked['progress']]);
    }

    public function testFullyCheckedMajorRevisionIs100(): void
    {
        $event = $this->event($this->checker(EventCheckSteps::FINISHED, 0, [
            'peer' => self::PEER_DONE, 'kw' => self::KW_DONE, 'cr' => ['memberID' => 40, 'done' => 2],
        ]));

        $data = ScriptureRevisionProgress::calculateEventProgress($event);
        $ch = $data['chapters'][1];

        foreach (['consume', 'selfEdit', 'peer', 'kwc', 'crc'] as $step) {
            $this->assertSame(S::FINISHED, $ch[$step]['state'], $step);
        }
        $this->assertSame(100, $ch['progress']);
        $this->assertSame([10, 20, 30, 40], array_keys($data['members']));
        // averaged over every chapter in the book, assigned or not
        $this->assertEquals(50, $data['overall_progress']);
        $this->assertEquals(50, ScriptureRevisionProgress::calculateEventProgress($event, true));
    }

    // --- Minor revision: no peer check

    public function testMinorRevisionSelfCheck(): void
    {
        $ch = $this->chapter1($this->checker(EventCheckSteps::SELF_CHECK, 1), RevisionMode::MINOR);

        $this->assertSame(25, $ch['progress']);
    }

    public function testMinorRevisionKeywordCheckedStartsTheChecking(): void
    {
        $ch = $this->checks(['kw' => ['memberID' => 30, 'done' => 1]], RevisionMode::MINOR);

        $this->assertSame(S::FINISHED, $ch['selfEdit']['state']);
        $this->assertSame(S::NOT_STARTED, $ch['peer']['state']);
        $this->assertEquals(62.5, $ch['progress']);
    }

    public function testMinorRevisionPeerCheckedHasNoHalfStep(): void
    {
        $ch = $this->checks(['peer' => ['memberID' => 20, 'done' => 1]], RevisionMode::MINOR);

        $this->assertSame(S::CHECKED, $ch['peer']['state']);
        $this->assertSame(50, $ch['progress']);
    }

    public function testFullyCheckedMinorRevisionIs100(): void
    {
        $ch = $this->checks(['kw' => self::KW_DONE, 'cr' => ['memberID' => 40, 'done' => 2]], RevisionMode::MINOR);

        $this->assertSame(100, $ch['progress']);
    }
}
