<?php

namespace Tests\Integration;

use App\Models\NewsModel;
use App\Models\ORM\Member;
use Helpers\Database;
use Support\Facades\Session;

/** Checks the test database setup itself. */
class DatabaseSetupTest extends DatabaseTestCase
{
    public function testRunsInTheSameSqlModeAsProduction(): void
    {
        $this->assertSame('', self::db()->selectOne('SELECT @@SESSION.sql_mode AS mode')->mode);
    }

    public function testAllModelLayersShareTheTestTransaction(): void
    {
        $memberID = $this->insert('members', ['userName' => 'jsmith', 'tempEmail' => '']);
        $this->insert('news', ['title' => 'Hello', 'text' => 'World', 'created_at' => '2024-01-01 10:00:00']);

        $this->assertSame('jsmith', Member::find($memberID)->userName, 'ORM model');
        $this->assertSame('Hello', (new NewsModel())->getNews()[0]->title, 'basic model');
        $this->assertCount(1, Database::get()->select('SELECT * FROM ' . PREFIX . 'news'), 'legacy database helper');
    }

    public function testStartsEmptyBecauseEachTestIsRolledBack(): void
    {
        // Runs after the test above, which inserted rows
        $this->assertSame(0, self::db()->table('members')->count());
        $this->assertSame(0, self::db()->table('news')->count());
    }

    public function testSessionIsFaked(): void
    {
        $this->session->set('memberID', 10);

        $this->assertSame(10, Session::get('memberID'));
        $this->assertSame('key(a=1)', __('key', ['a' => 1]));
    }
}
