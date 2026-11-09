<?php

namespace Tests\Integration;

use App\Domain\EventContributors;
use App\Domain\ProjectContributors;
use App\Models\ORM\Event;
use App\Models\ORM\Project;

/** Contributor lists used in export manifests and on the admin contributors page. */
class ContributorsTest extends DatabaseTestCase
{
    use Fixtures;

    private int $admin;
    private int $translator;
    private int $checker;
    private int $reviser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->language('en', 'English');
        $this->book('jud', 'Jude', 1, 65);
        $this->book('3jn', '3 John', 1, 64);

        $this->admin = $this->member(['userName' => 'admin', 'firstName' => 'anna', 'lastName' => 'admin']);
        $this->translator = $this->member(['userName' => 'tran', 'firstName' => 'tom', 'lastName' => 'TRANSLATOR']);
        $this->checker = $this->member(['userName' => 'check', 'firstName' => 'carl', 'lastName' => 'checker']);
        $this->reviser = $this->member(['userName' => 'rev', 'firstName' => 'rita', 'lastName' => 'reviser']);
        $this->profile($this->checker, [], ['church_role' => '["Elder","Pastor"]']);
    }

    /** An event of $bookProject: admin, translator of chapter 1 with $checks, reviser and reader at levels 2 and 3. */
    private function eventWithTeam(string $bookProject, array $checks, string $bookCode = 'jud', ?int $projectID = null): int
    {
        $eventID = $this->event($projectID ?? $this->project(['bookProject' => $bookProject]), ['bookCode' => $bookCode]);
        $this->eventAdmin($this->admin, $eventID);
        $trID = $this->translator($this->translator, $eventID, $checks);
        $this->chapter($trID, 1, ['l2memberID' => $this->reviser, 'l3memberID' => $this->reviser]);
        $this->checkerL2($this->reviser, $eventID, ['kwCheck' => [1 => ['memberID' => $this->checker, 'done' => 1]]]);
        $this->checkerL3($this->reviser, $eventID, ['peerCheck' => [1 => ['memberID' => $this->checker, 'done' => 2]]]);
        return $eventID;
    }

    private function contributors(int $eventID, int $level, string $mode, bool $withRoles = true): array
    {
        return (new EventContributors(Event::find($eventID), $level, $mode, $withRoles))->get();
    }

    // --- One event

    public function testLevelOneScriptureContributors(): void
    {
        $eventID = $this->eventWithTeam('ulb', ['peerCheck' => [1 => ['memberID' => $this->checker, 'done' => 2]]]);

        $result = $this->contributors($eventID, 1, 'ulb');

        $this->assertSame([$this->admin], array_keys($result['admins']));
        $this->assertSame([$this->translator], array_keys($result['translators']));
        $this->assertSame([$this->checker], array_keys($result['checkers']));
        $this->assertSame(['Tom', 'Translator', 'tran', ''], array_values(array_slice($result['translators'][$this->translator], 0, 4)));
        $this->assertSame('pastor', $result['checkers'][$this->checker]['role'], 'the highest church role');
        $this->assertSame('', $this->contributors($eventID, 1, 'ulb', false)['checkers'][$this->checker]['role']);
    }

    public function testLevelOneResourceCheckersComeFromTheirChecks(): void
    {
        foreach (['tn', 'tq', 'obs', 'bc', 'bca'] as $bookProject) {
            $eventID = $this->eventWithTeam($bookProject, [
                'otherCheck' => [1 => ['memberID' => $this->checker, 'done' => 3]],
                'peerCheck' => [1 => ['memberID' => $this->reviser, 'done' => 1]],
            ]);

            $this->assertSame([$this->checker, $this->reviser], array_keys($this->contributors($eventID, 1, $bookProject)['checkers']), $bookProject);
        }
    }

    public function testLevelTwoAndThreeCheckers(): void
    {
        $eventID = $this->eventWithTeam('ulb', []);

        $levelTwo = $this->contributors($eventID, 2, 'ulb');
        $this->assertSame([$this->checker, $this->reviser], array_keys($levelTwo['checkers']), 'revisers and their checkers');
        $this->assertSame([], $levelTwo['translators']);

        $this->assertSame([$this->checker, $this->reviser], array_keys($this->contributors($eventID, 3, 'ulb')['checkers']), 'reviewers and readers');
        $this->assertSame([$this->translator], array_keys($this->contributors($eventID, 2, 'tn')['translators']), 'help tools have no level 2');
    }

    // --- Whole project

    private function projectContributors(int $projectID, bool $withAdmins = true): array
    {
        return array_map(fn($c) => $c['uname'] == '---' ? $c['fname'] . ' ' . $c['lname'] : $c['uname'],
            (new ProjectContributors(Project::find($projectID), false, $withAdmins))->get());
    }

    public function testProjectContributorsFromAllLevelsAndUnregisteredCheckers(): void
    {
        $projectID = $this->project();
        $this->eventWithTeam('ulb', ['verbCheck' => [1 => ['memberID' => 'pastor john', 'done' => 1]]], 'jud', $projectID);

        $this->assertSame(['admin', 'check', 'Pastor John', 'rev', 'tran'], $this->projectContributors($projectID), 'sorted by first name');
        $this->assertSame(['check', 'Pastor John', 'rev', 'tran'], $this->projectContributors($projectID, false));
    }

    public function testProjectContributorsIncludeTheAdminsOfEveryEvent(): void
    {
        $projectID = $this->project();
        $first = $this->event($projectID, ['bookCode' => 'jud']);
        $second = $this->event($projectID, ['bookCode' => '3jn']);
        $this->chapter($this->translator($this->translator, $first), 1);
        $this->eventAdmin($this->admin, $first);
        $this->eventAdmin($this->member(['userName' => 'second', 'firstName' => 'zoe']), $second);

        $this->assertSame(['admin', 'tran', 'second'], $this->projectContributors($projectID), 'sorted by first name');
    }
}
