<?php

namespace Tests\Http;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/** Who may open which pages. */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class AccessTest extends HttpTestCase
{
    private function memberWithProfile(array $row = []): int
    {
        $memberID = $this->member($row);
        $this->profile($memberID, ['en' => [5, 3]], ['complete' => 1, 'proj_lang' => 'en']);
        return $memberID;
    }

    public function testPublicPages(): void
    {
        $this->assertStatus(200, $home = $this->get('/'));
        $this->assertSee('V-MAST', $home);
        $this->assertStatus(200, $this->get('/members/login'));
        $this->assertStatus(200, $this->get('/events/demo/pray'));
        $this->assertStatus(404, $this->get('/does-not-exist'));
    }

    public function testSignedOutVisitorsAreSentToLogin(): void
    {
        foreach (['/events', '/admin', '/members/profile', '/members'] as $page) {
            $this->assertRedirectedTo('members/login', $this->get($page));
        }
    }

    public function testMemberWithIncompleteProfileIsSentToTheirProfile(): void
    {
        $this->profile($memberID = $this->member(), []);
        $this->loginAs($memberID);

        $this->assertRedirectedTo('members/profile', $this->get('/events'));
        $this->assertStatus(200, $this->get('/members/profile'));
    }

    public function testMemberSeesTheirEvents(): void
    {
        $this->language('en', 'English');
        $this->book('gen', 'Genesis', 50, 1);
        $memberID = $this->memberWithProfile();
        $this->translator($memberID, $this->event($this->project()), ['step' => 'multi-draft', 'currentChapter' => 1]);
        $this->loginAs($memberID);

        $events = $this->get('/events');

        $this->assertStatus(200, $events);
        $this->assertSee('Genesis', $events);
    }

    public function testAdminPagesNeedAnAdmin(): void
    {
        $this->loginAs($this->memberWithProfile());
        $this->assertRedirectedTo('', $this->get('/admin'));

        $superAdmin = $this->memberWithProfile();
        $this->insert('super_admins', ['memberID' => $superAdmin]);
        $this->loginAs($superAdmin);
        $this->assertStatus(200, $this->get('/admin'));
    }
}
