<?php

namespace Tests\Integration;

use App\Models\ORM\BookInfo;
use App\Models\ORM\Event;
use App\Models\ORM\GatewayLanguage;
use App\Models\ORM\Language;
use App\Models\ORM\Member;
use App\Models\ORM\Preference;
use App\Models\ORM\Project;
use App\Models\ORM\Source;
use App\Models\ORM\SuperAdmin;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\BookInfo\BookInfoRepository;
use App\Repositories\Event\EventRepository;
use App\Repositories\GatewayLanguage\GatewayLanguageRepository;
use App\Repositories\Language\LanguageRepository;
use App\Repositories\Member\MemberRepository;
use App\Repositories\Preferences\PreferenceRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Source\SourceRepository;

class RepositoriesTest extends DatabaseTestCase
{
    use Fixtures;

    // --- Languages and sources (synced from external catalogs)

    private function catalogLanguage(string $lc, string $ln, int $gw = 0, string $ld = 'ltr'): object
    {
        return (object)['lc' => $lc, 'ln' => $ln, 'ang' => "$ln (en)", 'gw' => $gw, 'ld' => $ld];
    }

    public function testLanguageLookups(): void
    {
        $repo = new LanguageRepository(new Language());
        $repo->create(['langID' => 'en', 'langName' => 'English', 'angName' => 'English', 'isGW' => 1]);
        $repo->create(['langID' => 'id', 'langName' => 'Bahasa Indonesia', 'angName' => 'Indonesian']);

        $this->assertSame('Bahasa Indonesia', $repo->get('id')->langName);
        $this->assertSame('id', $repo->getByName('Bahasa Indonesia')->langID);
        $this->assertSame(['en'], $repo->getGwLanguages()->map(fn($l) => $l->langID)->toArray());
        $this->assertSame(['id'], $repo->getTargetLanguages()->map(fn($l) => $l->langID)->toArray());
    }

    public function testLanguageSyncAddsNewAndUpdatesExisting(): void
    {
        $repo = new LanguageRepository(new Language());
        $repo->create(['langID' => 'en', 'langName' => 'Old name', 'angName' => 'English']);

        $repo->upsertAll([$this->catalogLanguage('en', 'English', 1), $this->catalogLanguage('ar', 'العربية', 0, 'rtl')]);

        $this->assertSame([['ar', 'العربية', 0, 'rtl'], ['en', 'English', 1, 'ltr']],
            array_map(fn($l) => [$l->langID, $l->langName, (int)$l->isGW, $l->direction], $repo->all()->sortBy('langID')->values()->all()));
    }

    public function testLanguageSyncSurvivesNamesOutsideTheBasicMultilingualPlane(): void
    {
        $repo = new LanguageRepository(new Language());
        $repo->create(['langID' => 'srb', 'langName' => 'Sora', 'angName' => 'Sora']);

        // Sora Sompeng script needs 4-byte UTF-8, which the utf8 (3-byte) tables cannot store
        $repo->upsertAll([$this->catalogLanguage('srb', '𑃐𑃚𑃝'), $this->catalogLanguage('zz', 'Later language')]);

        $this->assertSame('Later language', $repo->get('zz')->langName, 'the rest of the catalog is still synced');
    }

    public function testSourceSyncAddsNewAndRenamesExisting(): void
    {
        $this->language('en', 'English');
        $repo = new SourceRepository(new Source());
        $repo->create(['langID' => 'en', 'slug' => 'ulb', 'name' => 'Old']);

        $repo->upsertAll([
            ['langID' => 'en', 'slug' => 'ulb', 'name' => 'Unlocked Literal Bible'],
            ['langID' => 'en', 'slug' => 'udb', 'name' => 'Unlocked Dynamic Bible'],
        ]);

        $this->assertSame('Unlocked Literal Bible', $repo->getByLangAndSlug('en', 'ulb')->name);
        $this->assertSame('Unlocked Dynamic Bible', $repo->getByLangAndSlug('en', 'udb')->name);
        $this->assertCount(2, $repo->all());
        $this->assertNull($repo->getByLangAndSlug('en', 'tn'));
    }

    // --- Members

    public function testCreateMemberWithProfileAndFindByEmailOrUserName(): void
    {
        $repo = new MemberRepository(new Member());

        $member = $repo->create(
            ['userName' => 'jsmith', 'email' => 'john@example.org', 'tempEmail' => ''],
            ['projects' => '[]', 'proj_lang' => 'en', 'prefered_roles' => '[]', 'languages' => '{}', 'mast_role' => '[]',
             'ref_person' => '', 'ref_email' => '', 'education' => '', 'ed_area' => '', 'ed_place' => '', 'church_role' => '[]']
        );

        $this->assertSame('en', $repo->get($member->memberID)->profile->proj_lang);
        $this->assertSame($member->memberID, $repo->getByUsername('jsmith')->memberID);
        $this->assertSame($member->memberID, $repo->getByEmail('john@example.org')->memberID);
        $this->assertSame($member->memberID, $repo->getByEmailOrUserName('john@example.org')->memberID);
        $this->assertSame($member->memberID, $repo->getByEmailOrUserName('jsmith')->memberID);
        $this->assertNull($repo->getByEmailOrUserName('nobody'));
    }

    public function testSuperAdminByMember(): void
    {
        $memberID = $this->member();
        $this->insert('super_admins', ['memberID' => $memberID]);

        $this->assertSame($memberID, (new AdminRepository(new SuperAdmin()))->getByMember($memberID)->memberID);
        $this->assertNull((new AdminRepository(new SuperAdmin()))->getByMember($this->member()));
    }

    // --- Preferences with defaults

    public function testPreferencesFallBackToDefaultsAndCanBeChanged(): void
    {
        $repo = new PreferenceRepository(new Preference());

        $this->assertSame('https://td.unfoldingword.org/exports/langnames.json', $repo->langNamesUrl()->prefValue);
        $this->assertSame('https://api.bibletranslationtools.org/v3/catalog.json', $repo->catalogUrl()->prefValue);
        $this->assertSame(2, self::db()->table('preferences')->count(), 'defaults are stored on first use');

        $repo->setCatalogUrl('https://catalog.test/v3.json');
        $repo->setLangNamesUrl('https://langs.test/names.json');

        $this->assertSame('https://catalog.test/v3.json', $repo->catalogUrl()->prefValue);
        $this->assertSame('https://langs.test/names.json', $repo->langNamesUrl()->prefValue);
        $this->assertSame(2, self::db()->table('preferences')->count());
    }

    public function testSettingAPreferenceBeforeItsFirstUseStoresIt(): void
    {
        $repo = new PreferenceRepository(new Preference());

        $repo->setCatalogUrl('https://catalog.test/v3.json');

        $this->assertSame('https://catalog.test/v3.json', $repo->catalogUrl()->prefValue);
    }

    // --- Gateway languages, projects, books and events

    public function testCreateProjectUnderGatewayLanguageAndEvent(): void
    {
        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('gen', 'Genesis', 50, 1);

        $gl = (new GatewayLanguageRepository(new GatewayLanguage()))->create(['gwLang' => 'en']);
        $project = (new ProjectRepository(new Project()))->create(
            ['gwLang' => 'en', 'targetLang' => 'id', 'sourceLangID' => 'en', 'bookProject' => 'ulb', 'sourceBible' => 'ulb'], $gl);
        $events = new EventRepository(new Event());
        $event = $events->create(['bookCode' => 'gen'], $project);

        $this->assertSame($gl->glID, (new ProjectRepository(new Project()))->get($project->projectID)->glID);
        $this->assertSame('Genesis', $events->get($event->eventID)->bookInfo->name);
        $this->assertSame('Indonesian', $events->get($event->eventID)->project->targetLanguage->langName);
        $this->assertSame(50, (new BookInfoRepository(new BookInfo()))->getByBookCode('gen')->chaptersNum);
    }

    // --- Progress from real ORM events (the unit tests build these objects by hand)

    public function testEventProgressAtEachLevelFromDatabaseRows(): void
    {
        $this->language('en', 'English');
        $this->book('jud', 'Jude', 1, 65);
        $eventID = $this->event($this->project(), ['bookCode' => 'jud']);
        $translator = $this->member();
        $trID = $this->translator($translator, $eventID, ['step' => 'self-check', 'currentChapter' => 1]);
        $this->chapter($trID, 1, ['chunks' => '[[1,2],[3]]', 'l2memberID' => $translator, 'l3memberID' => $translator,
            'l2chID' => $this->checkerL2($translator, $eventID, ['step' => 'self-check', 'currentChapter' => 1]),
            'l3chID' => $this->checkerL3($translator, $eventID, ['step' => 'finished',
                'peerCheck' => [1 => ['memberID' => $this->member(), 'done' => 2]]])]);
        $this->translation($trID, 1, 0);
        $this->translation($trID, 1, 1);

        $repo = new EventRepository(new Event());
        $event = fn() => $repo->get($eventID);

        // all chunks drafted (11), consume (11) and chunking (11); self edit still in progress
        $this->assertEquals(33, $repo->calculateEventProgress($event(), 'l1'));
        // revision: consume done (20), self check in progress
        $this->assertEquals(20, $repo->calculateEventProgress($event(), 'l2'));
        // review: peer review and edit finished
        $this->assertEquals(100, $repo->calculateEventProgress($event(), 'l3'));
    }

    // --- Notifications

    public function testNotificationsByIdSenderAndRecipient(): void
    {
        $this->language('en', 'English');
        $events = new EventRepository(new Event());
        $eventID = $this->event($this->project());
        $otherEventID = $this->event($this->project());
        $sender = Member::find($this->member());
        $recipient = Member::find($this->member());
        $this->translator($recipient->memberID, $eventID);

        $toRecipient = $events->createNotification(['step' => 'peer-review', 'currentChapter' => 1, 'type' => 'started'],
            Event::find($eventID), $sender, $recipient);
        $toEveryone = $events->createNotification(['step' => 'keyword-check', 'currentChapter' => 2, 'type' => 'ready'],
            Event::find($eventID), $sender, null);
        $events->createNotification(['step' => 'peer-review', 'currentChapter' => 1, 'type' => 'started'],
            Event::find($otherEventID), $sender, null);

        $ids = fn($notes) => $notes->map(fn($n) => $n->noteID)->values()->toArray();
        $this->assertSame([$toRecipient->noteID], $ids($events->getNotification($toRecipient->noteID)));
        $this->assertCount(3, $events->getFromNotifications($sender->memberID));
        $this->assertSame([$toRecipient->noteID, $toEveryone->noteID], $ids($events->getToNotifications($recipient->memberID)),
            'addressed to them or to everyone, only in their events');

        $this->assertTrue($events->notificationExists($eventID, $sender->memberID, $recipient->memberID, null, 'peer-review', 1, 'started'));
        $this->assertFalse($events->notificationExists($eventID, $sender->memberID, $recipient->memberID, null, 'peer-review', 1, 'done'));
    }
}
