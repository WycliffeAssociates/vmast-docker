<?php

namespace Tests\Integration;

use Container\Container;
use Database\Connection;
use Database\ConnectionFactory;
use Database\DatabaseManager;
use Database\Model as BasicModel;
use Database\ORM\Model;
use Helpers\Database;
use PDO;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Support\Facades\Facade;
use Tests\FakeLanguage;
use Tests\FakeSession;

/**
 * Base for tests against the test database (compose.test.yml, schema only).
 *
 * Connects the ORM, basic and legacy model layers the way DatabaseServiceProvider does, without booting the app.
 * Every test runs inside a transaction that is rolled back, so tests start from an empty database and never see
 * each other's rows. Auto-increment counters do advance, so never assume specific IDs.
 */
abstract class DatabaseTestCase extends TestCase
{
    private static ?DatabaseManager $manager = null;

    protected FakeSession $session;

    protected function setUp(): void
    {
        self::db()->beginTransaction();

        $this->session = new FakeSession();
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(['language' => new FakeLanguage(), 'session' => $this->session]);
    }

    protected function tearDown(): void
    {
        self::db()->rollBack();

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
    }

    protected static function db(): Connection
    {
        return self::manager()->connection();
    }

    /** Inserts a row into $table (without the vm_ prefix) and returns its ID. */
    protected function insert(string $table, array $row): int
    {
        return (int)self::db()->table($table)->insertGetId($row);
    }

    private static function manager(): DatabaseManager
    {
        if (self::$manager) {
            return self::$manager;
        }

        $name = getenv('DB_NAME');
        if (!getenv('DB_HOST') || !$name) {
            throw new RuntimeException(
                'Integration tests need the test database: run `make test`, or set DB_HOST, DB_NAME, DB_USER and DB_PASS.'
            );
        }
        // Rolled-back transactions keep data safe, but schema-level mistakes would not be: never touch a real database
        if (!str_ends_with($name, '_test')) {
            throw new RuntimeException("Refusing to run integration tests against '$name': its name must end with _test.");
        }

        defined('PREFIX') || define('PREFIX', 'vm_');

        $app = new Container();
        // DatabaseManager reads these dotted keys literally, so a plain array is enough
        $app['config'] = [
            'database.fetch' => PDO::FETCH_CLASS,
            'database.default' => 'mysql',
            'database.connections' => ['mysql' => [
                'driver' => 'mysql',
                'hostname' => getenv('DB_HOST'),
                'database' => $name,
                'username' => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
                'prefix' => PREFIX,
                'charset' => 'utf8',
                'collation' => 'utf8_general_ci',
            ]],
        ];

        self::$manager = new DatabaseManager($app, new ConnectionFactory($app));
        Model::setConnectionResolver(self::$manager);
        BasicModel::setConnectionResolver(self::$manager);
        Database::setConnectionResolver(self::$manager);

        return self::$manager;
    }
}
