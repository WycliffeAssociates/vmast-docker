<?php

namespace Tests\Integration;

use App\Models\MembersModel;

class MembersModelTest extends DatabaseTestCase
{
    use Fixtures;

    private MembersModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new MembersModel();
        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
    }

    private function userNames(array $members): array
    {
        return array_map(fn($m) => $m->userName, $members);
    }

    // --- Lookups

    public function testGetMemberWithEitherWhereForm(): void
    {
        $id = $this->member(['userName' => 'jsmith', 'firstName' => 'John']);
        $this->member(['userName' => 'mjones']);

        $this->assertSame('John', $this->model->getMember(['firstName'], ['memberID', $id])[0]->firstName);
        $this->assertSame('John', $this->model->getMember(['firstName'], [['userName', 'jsmith'], ['memberID', '>', 0]])[0]->firstName);
        $this->assertSame([], $this->model->getMember(['firstName'], ['userName', 'nobody']));
    }

    public function testGetMemberWithProfile(): void
    {
        $id = $this->member();
        $this->profile($id, ['id' => [3, 2]], ['proj_lang' => 'id']);

        $member = $this->model->getMember(['members.userName'], ['members.memberID', $id], true)[0];

        $this->assertSame('id', $member->proj_lang);
        $this->assertSame('{"id":[3,2]}', $member->languages);
    }

    public function testGetMembersByIdsOrderedByFirstName(): void
    {
        $zoe = $this->member(['firstName' => 'Zoe', 'email' => 'zoe@example.org']);
        $amy = $this->member(['firstName' => 'Amy']);
        $this->member(['firstName' => 'Bob']);
        $this->profile($zoe, [], ['proj_lang' => 'en']);

        $members = $this->model->getMembers([$zoe, $amy]);
        $this->assertSame(['Amy', 'Zoe'], array_map(fn($m) => $m->firstName, $members));
        $this->assertFalse(isset($members[1]->email));

        $more = $this->model->getMembers([$zoe], true, true)[0];
        $this->assertSame(['zoe@example.org', 'en'], [$more->email, $more->proj_lang]);

        $this->assertSame([], $this->model->getMembers([]));
    }

    public function testMembersByTermOnlyFindsVerifiedMembers(): void
    {
        $this->member(['userName' => 'jsmith', 'firstName' => 'John', 'lastName' => 'Smith']);
        $this->member(['userName' => 'johnny', 'firstName' => 'Unconfirmed', 'lastName' => 'User', 'verified' => 0]);
        $this->member(['userName' => 'pending', 'firstName' => 'John', 'lastName' => 'Pending', 'verified' => 0]);

        $this->assertSame(['jsmith'], $this->userNames($this->model->getMembersByTerm('john')));
        $this->assertSame([], $this->model->getMembersByTerm('pending'));
    }

    // --- Member search

    public function testSearchByNameInAnyNameField(): void
    {
        $this->member(['userName' => 'jsmith', 'firstName' => 'John', 'lastName' => 'Smith']);
        $this->member(['userName' => 'mary', 'firstName' => 'Mary', 'lastName' => 'Johnson']);
        $this->member(['userName' => 'paul', 'firstName' => 'Paul', 'lastName' => 'Brown']);

        $this->assertSame(['jsmith', 'mary'], $this->userNames($this->model->searchMembers('john', 'all', [])));
        $this->assertSame(2, $this->model->searchMembers('john', 'all', [], true));
    }

    public function testSearchVerifiedOnlyAndEmailForAdmins(): void
    {
        $this->member(['userName' => 'amy', 'email' => 'amy@example.org']);
        $this->member(['userName' => 'bob', 'verified' => 0]);

        $this->assertSame(['amy', 'bob'], $this->userNames($this->model->searchMembers(null, 'all', [])));
        $this->assertSame(['amy'], $this->userNames($this->model->searchMembers(null, 'all', [], false, false, true)));
        $this->assertFalse(isset($this->model->searchMembers(null, 'all', [])[0]->email));
        $this->assertSame('amy@example.org', $this->model->searchMembers(null, 'all', [], false, true)[0]->email);
    }

    public function testSearchPagesByUserName(): void
    {
        foreach (['carl', 'amy', 'dave', 'bob'] as $userName) {
            $this->member(['userName' => $userName]);
        }

        $this->assertSame(['amy', 'bob'], $this->userNames($this->model->searchMembers(null, 'all', [], false, false, false, 1, 2)));
        $this->assertSame(['carl', 'dave'], $this->userNames($this->model->searchMembers(null, 'all', [], false, false, false, 2, 2)));
        $this->assertSame(4, $this->model->searchMembers(null, 'all', [], true, false, false, 2, 2), 'count ignores paging');
    }

    public function testSearchTranslatorsByProfileLanguage(): void
    {
        $this->profile($this->member(['userName' => 'indo']), ['id' => [4, 3]], ['proj_lang' => 'id']);
        $this->profile($this->member(['userName' => 'both']), ['en' => [5, 3], 'id' => [2, 1]]);
        $this->profile($this->member(['userName' => 'eng']), ['en' => [5, 3]]);
        $this->member(['userName' => 'noprofile']);

        $this->assertSame(['both', 'indo'], $this->userNames($this->model->searchMembers(null, 'translators', ['id'])));
        $this->assertSame(['both', 'eng', 'indo'], $this->userNames($this->model->searchMembers(null, 'translators', ['id', 'en'])));
        $this->assertSame('Indonesian', $this->model->searchMembers('indo', 'translators', ['id'])[0]->langName);
        $this->assertSame(2, $this->model->searchMembers(null, 'translators', ['id'], true));
    }

    public function testSearchFacilitatorsAreEventAdminsOfEventsInTheLanguage(): void
    {
        $indonesianEvent = $this->event($this->project(['targetLang' => 'id']));
        $englishEvent = $this->event($this->project(['targetLang' => 'en', 'gwLang' => 'en']));

        $this->eventAdmin($idAdmin = $this->member(['userName' => 'id_admin']), $indonesianEvent);
        $this->eventAdmin($idAdmin, $this->event($this->project(['targetLang' => 'id']), ['bookCode' => 'exo']));
        $this->eventAdmin($this->member(['userName' => 'en_admin']), $englishEvent);
        $this->profile($this->member(['userName' => 'id_speaker']), ['id' => [4, 3]]);

        $search = fn($role, $languages, $count = false) => $count
            ? $this->model->searchMembers(null, $role, $languages, true)
            : $this->userNames($this->model->searchMembers(null, $role, $languages));

        $this->assertSame(['en_admin', 'id_admin'], $search('facilitators', []), 'all event admins');
        $this->assertSame(['id_admin'], $search('facilitators', ['id']), 'admins of events in the language, once each');
        $this->assertSame(1, $search('facilitators', ['id'], true));
        $this->assertSame(['id_speaker'], $search('translators', ['id']), 'translators by profile language');
        $this->assertSame(['id_admin', 'id_speaker'], $search('all', ['id']), 'speakers of the language and its facilitators');
        $this->assertSame(2, $search('all', ['id'], true));
    }

    // --- Create and update

    public function testCreateAndUpdateMemberAndProfile(): void
    {
        $id = $this->model->createMember(['userName' => 'new', 'tempEmail' => '', 'email' => 'new@example.org']);
        $this->assertSame(1, $this->model->updateMember(['firstName' => 'Neo'], ['memberID' => $id]));

        $profileID = $this->model->createProfile(['mID' => $id, 'projects' => '[]', 'proj_lang' => 'en', 'prefered_roles' => '[]',
            'languages' => '{}', 'mast_role' => '[]', 'ref_person' => '', 'ref_email' => '', 'education' => '', 'ed_area' => '',
            'ed_place' => '', 'church_role' => '[]']);
        $this->assertSame(1, $this->model->updateProfile(['complete' => 1], ['pID' => $profileID]));

        $member = $this->model->getMember(['members.firstName', 'profile.complete'], ['members.memberID', $id], true)[0];
        $this->assertSame(['Neo', 1], [$member->firstName, (int)$member->complete]);
    }

    public function testCreateMultipleTestMembersContinuesTheNumbering(): void
    {
        $this->member(['userName' => 'user7']);
        $this->member(['userName' => 'user12']);
        $_ENV['MAIN_HOST'] = 'vmast.test';

        $this->assertSame('', $this->model->createMultipleMembers(2), 'nothing without a password');
        $this->assertSame('The members (user13 to user14) have been created!', $this->model->createMultipleMembers(2, ['id' => [3, 3]], 'hash'));
        $this->assertSame(['user12', 'user13', 'user14', 'user7'], self::db()->table('members')->orderBy('userName')->lists('userName'));

        $created = self::db()->table('members')->leftJoin('profile', 'members.memberID', '=', 'profile.mID')
            ->where('userName', 'user14')->first();
        $this->assertSame(['user14@vmast.test', 'hash', '{"id":[3,3]}', '["translator"]'],
            [$created->email, $created->password, $created->languages, $created->prefered_roles]);
    }

    // --- TURN server secret (a coturn table without the vm_ prefix)

    public function testTurnSecretOfThisHost(): void
    {
        $_ENV['MAIN_HOST'] = 'vmast.test';
        self::db()->insert("INSERT INTO turn_secret (realm, value, expire) VALUES ('vmast.test', 'secret', 100), ('other.host', 'x', 0)");

        $this->assertSame('secret', $this->model->getTurnSecret()[0]->value);
        $this->assertSame(1, $this->model->updateTurnSecret(['value' => 'rotated', 'expire' => 200]));
        $this->assertSame([['rotated', 200]], array_map(fn($s) => [$s->value, $s->expire], $this->model->getTurnSecret()));
        $this->assertSame(0, self::db()->table('members')->count(), 'the vm_ prefix is restored afterwards');
    }

    // --- Passwords

    public function testGeneratedPasswordHasTheLengthAndEveryCharacterSet(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $password = $this->model->generateStrongPassword(22);

            $this->assertSame(22, strlen($password));
            $this->assertMatchesRegularExpression('/[a-z]/', $password);
            $this->assertMatchesRegularExpression('/[A-Z]/', $password);
            $this->assertMatchesRegularExpression('/[2-9]/', $password);
            $this->assertMatchesRegularExpression('/[!@#$%&*?\-]/', $password);
            $this->assertDoesNotMatchRegularExpression('/[ilo01IO]/', $password, 'look-alike characters are left out');
        }
    }

    public function testGeneratedPasswordWithChosenSetsAndDashes(): void
    {
        $this->assertMatchesRegularExpression('/^[2-9]{6}$/', $this->model->generateStrongPassword(6, false, 'd'));
        $this->assertMatchesRegularExpression('/^[a-z]{3}-[a-z]{3}-[a-z]{3}$/', $this->model->generateStrongPassword(9, true, 'l'));
    }
}
