<?php

namespace Tests\Http;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Admin actions: gateway languages, projects, events, members, and event membership.
 * English gateway language with its admin; project P (English → Indonesian) with its admin and a Jude event;
 * project Q (English → Russian) with another admin; a super admin and a member without any role.
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class AdminTest extends HttpTestCase
{
    private int $glID;
    private int $projectP;
    private int $projectQ;
    private int $eventP;
    /** @var array<string, int> */
    private array $members = [];

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['en' => 'English', 'id' => 'Indonesian', 'ru' => 'Russian', 'fr' => 'French'] as $code => $name) {
            $this->language($code, $name);
        }
        $this->book('jud', 'Jude', 1, 65);
        $this->book('3jn', '3 John', 1, 64);

        foreach (['superAdmin', 'glAdmin', 'projectAdmin', 'otherAdmin', 'member'] as $role) {
            $this->members[$role] = $this->member(['firstName' => ucfirst($role), 'lastName' => 'Smith']);
            $this->profile($this->members[$role], [], ['complete' => 1]);
        }
        $this->insert('super_admins', ['memberID' => $this->members['superAdmin']]);

        $this->glID = $this->insert('gateway_languages', ['gwLang' => 'en']);
        self::db()->table('member_gl')->insert(['memberID' => $this->members['glAdmin'], 'glID' => $this->glID]);
        $this->projectP = $this->project(['glID' => $this->glID, 'targetLang' => 'id']);
        $this->projectQ = $this->project(['glID' => $this->glID, 'targetLang' => 'ru']);
        self::db()->table('member_project')->insert([
            ['memberID' => $this->members['projectAdmin'], 'projectID' => $this->projectP],
            ['memberID' => $this->members['otherAdmin'], 'projectID' => $this->projectQ],
        ]);
        $this->eventP = $this->event($this->projectP, ['bookCode' => 'jud']);
        $this->eventAdmin($this->members['projectAdmin'], $this->eventP);
    }

    private function as(string $role, string $endpoint, array $data): array
    {
        $_SESSION = [];
        $this->loginAs($this->members[$role]);
        $content = (string)$this->post($endpoint, $data, true)->getContent();
        return $content === '' ? [] : json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    private function rows(string $table, array $where = []): int
    {
        return self::db()->table($table)->where($where)->count();
    }

    // --- Gateway languages and projects

    public function testOnlySuperAdminsCreateGatewayLanguages(): void
    {
        $this->assertSame([], $this->as('glAdmin', '/admin/rpc/create_gateway_language', ['gwLang' => 'ru']));
        $this->assertSame(0, $this->rows('gateway_languages', ['gwLang' => 'ru']));

        $this->assertArrayHasKey('success', $this->as('superAdmin', '/admin/rpc/create_gateway_language', ['gwLang' => 'ru']));
        $this->assertStringContainsString('alert', $this->as('superAdmin', '/admin/rpc/create_gateway_language', ['gwLang' => 'ru'])['error']);
        $this->assertSame(1, $this->rows('gateway_languages', ['gwLang' => 'ru']));
    }

    private function createProject(string $role, int $glID, string $targetLang): array
    {
        return $this->as($role, '/admin/rpc/create_project', ['projectMode' => 'bible', 'subGwLangs' => "en|$glID",
            'targetLangs' => $targetLang, 'sourceTranslation' => 'ulb|en', 'projectType' => 'udb',
            'project_admins' => [$this->members['otherAdmin']]]);
    }

    public function testGatewayLanguageAdminsCreateProjectsInTheirLanguage(): void
    {
        $this->assertArrayHasKey('success', $this->createProject('glAdmin', $this->glID, 'fr'));

        $project = self::db()->table('projects')->where(['targetLang' => 'fr', 'bookProject' => 'udb'])->first();
        $this->assertSame([$this->members['otherAdmin']], array_map('intval',
            self::db()->table('member_project')->where('projectID', $project->projectID)->lists('memberID')));

        $this->assertStringContainsString('Project exists', $this->createProject('glAdmin', $this->glID, 'fr')['error']);
    }

    public function testGatewayLanguageAdminsCannotCreateProjectsInAnotherLanguage(): void
    {
        $frGlID = $this->insert('gateway_languages', ['gwLang' => 'fr']);
        $frAdmin = $this->member();
        self::db()->table('member_gl')->insert(['memberID' => $frAdmin, 'glID' => $frGlID]);
        $this->members['frAdmin'] = $frAdmin;

        $this->assertStringContainsString('Not enough rights', $this->createProject('frAdmin', $this->glID, 'fr')['error']);
        $this->assertSame(['login' => true], $this->createProject('member', $this->glID, 'fr'));
        $this->assertSame(0, $this->rows('projects', ['targetLang' => 'fr']));
    }

    // --- Events

    private function eventAction(string $role, int $projectID, string $bookCode, string $act): array
    {
        return $this->as($role, '/admin/rpc/create_event', ['projectID' => $projectID, 'book_code' => $bookCode,
            'act' => $act, 'admins' => [$this->members[$role]]]);
    }

    public function testProjectAdminsCreateAndDeleteEventsOfTheirProject(): void
    {
        // The book's source text must be available; it is normally downloaded and cached
        $this->app['cache']->put('en_ulb_3jn', 'usfm', 60);

        $this->assertArrayHasKey('success', $this->eventAction('projectAdmin', $this->projectP, '3jn', 'create'));
        $eventID = self::db()->table('events')->where(['projectID' => $this->projectP, 'bookCode' => '3jn'])->pluck('eventID');
        $this->assertSame(1, $this->rows('member_event', ['eventID' => $eventID, 'memberID' => $this->members['projectAdmin']]));

        $this->assertStringContainsString('already', $this->eventAction('projectAdmin', $this->projectP, '3jn', 'create')['error']);

        $this->assertArrayHasKey('success', $this->eventAction('projectAdmin', $this->projectP, '3jn', 'delete'));
        $this->assertSame(0, $this->rows('events', ['eventID' => $eventID]));
    }

    public function testAdminsCannotChangeEventsOfAnotherProject(): void
    {
        $this->app['cache']->put('en_ulb_3jn', 'usfm', 60);

        $allowed = [];
        foreach (['create' => '3jn', 'edit' => 'jud', 'delete' => 'jud'] as $act => $book) {
            if (!isset($this->eventAction('otherAdmin', $this->projectP, $book, $act)['error'])) {
                $allowed[] = $act;
            }
        }
        $this->assertSame([], $allowed, 'Actions allowed to an admin of another project');
        $this->assertSame(1, $this->rows('events', ['projectID' => $this->projectP]));
        $this->assertSame([$this->members['projectAdmin']], array_map('intval',
            self::db()->table('member_event')->where('eventID', $this->eventP)->lists('memberID')));
    }

    public function testEventContributorsAreOnlyForAdminsOfTheEvent(): void
    {
        $this->assertTrue($this->as('projectAdmin', '/admin/rpc/get_event_contributors', ['eventID' => $this->eventP])['success']);

        $answer = $this->as('otherAdmin', '/admin/rpc/get_event_contributors', ['eventID' => $this->eventP]);
        $this->assertFalse($answer['success']);
        $this->assertArrayNotHasKey('admins', $answer, 'Contributor names and emails');
    }

    public function testProjectPageAndProgressAreOnlyForAdminsOfTheProject(): void
    {
        foreach (['projectAdmin', 'glAdmin'] as $role) {
            $this->loginAs($this->members[$role]);
            $this->assertStatus(200, $this->get("/admin/project/$this->projectP"));
            $this->assertTrue($this->as($role, "/admin/rpc/get_event_progress/$this->eventP", [])['success']);
        }

        $this->loginAs($this->members['otherAdmin']);
        $this->assertRedirectedTo('', $this->get("/admin/project/$this->projectP"));
        $this->assertFalse($this->as('otherAdmin', "/admin/rpc/get_event_progress/$this->eventP", [])['success']);
    }

    // --- Members

    public function testOnlySuperAdminsVerifyAndBlockMembers(): void
    {
        $memberID = $this->members['member'];
        foreach (['glAdmin', 'projectAdmin'] as $role) {
            $this->assertSame(['success' => false, 'error' => 'not_allowed'], $this->as($role, '/admin/rpc/block_member', ['memberID' => $memberID]));
            $this->assertSame(['success' => false, 'error' => 'not_allowed'], $this->as($role, '/admin/rpc/verify_member', ['memberID' => $memberID]));
        }

        $this->assertSame(['success' => true, 'blocked' => true], $this->as('superAdmin', '/admin/rpc/block_member', ['memberID' => $memberID]));
        $this->assertSame(['success' => true, 'blocked' => false], $this->as('superAdmin', '/admin/rpc/block_member', ['memberID' => $memberID]));
        $this->assertSame(['success' => true], $this->as('superAdmin', '/admin/rpc/verify_member', ['memberID' => $memberID]));
        $this->assertSame([1, 1], array_map('intval', array_values((array)self::db()->table('members')
            ->where('memberID', $memberID)->first(['verified', 'active']))));
    }

    // --- Event membership

    public function testEventAdminsAddAndRemoveTranslators(): void
    {
        $add = ['eventID' => $this->eventP, 'userType' => 'translator', 'memberID' => $this->members['member']];
        $remove = ['eventID' => $this->eventP, 'memberID' => $this->members['member']];

        $this->assertStringContainsString('Not enough rights', $this->as('otherAdmin', '/events/rpc/add_event_member', $add)['error'][0]);
        $this->assertArrayHasKey('success', $this->as('projectAdmin', '/events/rpc/add_event_member', $add));
        $this->assertSame(1, $this->rows('translators', ['eventID' => $this->eventP, 'memberID' => $this->members['member']]));

        $this->assertFalse($this->as('otherAdmin', '/events/rpc/delete_event_member', $remove)['success']);
        $this->assertTrue($this->as('projectAdmin', '/events/rpc/delete_event_member', $remove)['success']);
        $this->assertSame(0, $this->rows('translators', ['eventID' => $this->eventP]));
    }
}
