<?php

namespace Tests\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Who may open which pages. One Genesis translation event into Indonesian with a translator and an event admin;
 * an outsider (a member not in the event) and a super admin (not an admin of the event).
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class AccessTest extends HttpTestCase
{
    private int $eventID;
    private int $projectID;
    private int $glID;
    /** @var array<string, int> */
    private array $members = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('gen', 'Genesis', 50, 1);
        $this->glID = $this->insert('gateway_languages', ['gwLang' => 'en']);
        $this->projectID = $this->project(['glID' => $this->glID, 'targetLang' => 'id']);
        $this->eventID = $this->event($this->projectID);

        foreach (['outsider', 'translator', 'eventAdmin', 'superAdmin'] as $role) {
            $this->members[$role] = $this->memberWithProfile();
        }
        $trID = $this->translator($this->members['translator'], $this->eventID, ['step' => 'multi-draft', 'currentChapter' => 1]);
        $this->chapter($trID, 1, ['chunks' => '[[1,2]]']);
        $this->eventAdmin($this->members['eventAdmin'], $this->eventID);
        $this->insert('super_admins', ['memberID' => $this->members['superAdmin']]);
    }

    private function memberWithProfile(): int
    {
        $memberID = $this->member();
        $this->profile($memberID, ['en' => [5, 3]], ['complete' => 1, 'proj_lang' => 'en']);
        return $memberID;
    }

    /** Replaces {event}, {project}, {gl} and {translator} in a path. */
    private function path(string $path): string
    {
        return strtr($path, ['{event}' => $this->eventID, '{project}' => $this->projectID, '{gl}' => $this->glID,
            '{translator}' => $this->members['translator']]);
    }

    private function as(string $role, string $path)
    {
        $_SESSION = [];
        if ($role !== 'anon') {
            $this->loginAs($this->members[$role]);
        }
        return $this->get($this->path($path));
    }

    // --- Signed out

    public function testPublicPages(): void
    {
        foreach (['/', '/about', '/contact', '/events/faq', '/members/login', '/members/signup', '/members/passwordreset',
                     '/events/demo/pray'] as $page) {
            $this->assertStatus(200, $this->as('anon', $page));
        }
        $this->assertSee('V-MAST', $this->as('anon', '/'));
        $this->assertStatus(404, $this->as('anon', '/does-not-exist'));
    }

    public function testEverythingElseNeedsALogin(): void
    {
        foreach (['/translations', '/translations/id', '/events', '/events/news', '/events/translator/{event}',
                     '/events/information/{event}', '/events/manage/{event}', '/members', '/members/profile',
                     '/members/profile/{translator}', '/members/search', '/admin', '/admin/members', '/admin/project/{project}',
                     '/admin/gateway_language/{gl}'] as $page) {
            $this->assertRedirectedTo('members/login', $this->as('anon', $page));
        }
    }

    // --- Signed in

    public function testSignedInMembersSkipTheGuestPages(): void
    {
        $this->assertRedirectedTo('events', $this->as('outsider', '/'));
        $this->assertRedirectedTo('members', $this->as('outsider', '/members/login'));
        $this->assertRedirectedTo('events', $this->as('outsider', '/members/signup'));
        $this->assertRedirectedTo('members', $this->as('outsider', '/members/passwordreset'));
    }

    public function testMemberWithIncompleteProfileIsSentToTheirProfile(): void
    {
        $this->profile($memberID = $this->member(), []);
        $this->loginAs($memberID);

        $this->assertRedirectedTo('members/profile', $this->get('/events'));
        $this->assertStatus(200, $this->get('/members/profile'));
    }

    public static function memberPages(): array
    {
        return [['/events'], ['/events/news'], ['/members/profile'], ['/translations'], ['/translations/id']];
    }

    #[DataProvider('memberPages')]
    public function testPagesForEveryMember(string $page): void
    {
        foreach (['outsider', 'translator', 'eventAdmin', 'superAdmin'] as $role) {
            $this->assertStatus(200, $this->as($role, $page));
        }
    }

    public function testOnlyTheTranslatorGetsTheTranslationPage(): void
    {
        $page = $this->as('translator', '/events/translator/{event}');
        $this->assertStatus(200, $page);
        $this->assertSee('Genesis', $page);

        foreach (['outsider', 'eventAdmin', 'superAdmin'] as $role) {
            $this->assertSee("You aren't participating in this book translation.", $this->as($role, '/events/translator/{event}'));
        }
    }

    public function testEventInformationIsForParticipantsAndAdmins(): void
    {
        foreach (['translator', 'eventAdmin'] as $role) {
            $this->assertSee('Genesis', $this->as($role, '/events/information/{event}'));
        }
        foreach (['outsider', 'superAdmin'] as $role) {
            $this->assertSee('You do not have permission to view information about this book.', $this->as($role, '/events/information/{event}'));
        }
    }

    public function testOnlyEventAdminsManageTheEvent(): void
    {
        $this->assertStatus(200, $this->as('eventAdmin', '/events/manage/{event}'));
        foreach (['outsider', 'translator', 'superAdmin'] as $role) {
            $this->assertRedirectedTo('events', $this->as($role, '/events/manage/{event}'));
        }
    }

    public function testPublicProfilesAreForAdmins(): void
    {
        foreach (['eventAdmin', 'superAdmin'] as $role) {
            $this->assertStatus(200, $this->as($role, '/members/profile/{translator}'));
        }
        foreach (['outsider', 'translator'] as $role) {
            $this->assertRedirectedTo('events', $this->as($role, '/members/profile/{translator}'));
        }
    }

    public function testAdminAreaIsForSuperAdmins(): void
    {
        foreach (['/admin', '/admin/members'] as $page) {
            $this->assertStatus(200, $this->as('superAdmin', $page));
            foreach (['outsider', 'translator', 'eventAdmin'] as $role) {
                $this->assertRedirectedTo('', $this->as($role, $page));
            }
        }
        $this->assertRedirectedTo('admin/members', $this->as('superAdmin', '/members/search'));
        $this->assertRedirectedTo('events', $this->as('outsider', '/members/search'));
    }

    public function testProjectPagesAreForGatewayLanguageAndProjectAdminsOnly(): void
    {
        // A super admin is not one of them unless also given that role
        foreach (['/admin/project/{project}', '/admin/gateway_language/{gl}'] as $page) {
            foreach (['outsider', 'eventAdmin', 'superAdmin'] as $role) {
                $this->assertRedirectedTo('', $this->as($role, $page));
            }
        }
    }
}
