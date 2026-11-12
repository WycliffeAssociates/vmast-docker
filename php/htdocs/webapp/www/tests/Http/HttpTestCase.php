<?php

namespace Tests\Http;

use App\Models\ORM\Member;
use Database\Connection;
use Exception\RedirectToException;
use Foundation\Application;
use Helpers\Session;
use Http\Request;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tests\Integration\Fixtures;

/**
 * Boots the real application (app/Boot/App.php) in the 'testing' environment and handles requests in-process,
 * the way Laravel 4's TestCase did. Each test class must run its tests in separate processes, because the app
 * defines global constants and facades on boot:
 *
 *     #[RunTestsInSeparateProcesses]
 *     #[PreserveGlobalState(false)]
 *
 * Every test runs inside a database transaction that is rolled back, against the test database (compose.test.yml).
 */
abstract class HttpTestCase extends TestCase
{
    use Fixtures;

    protected Application $app;

    protected function setUp(): void
    {
        if (!getenv('DB_HOST') || !str_ends_with((string)getenv('DB_NAME'), '_test')) {
            throw new RuntimeException('HTTP tests need the test database: run `make test`.');
        }

        // Templates call setcookie() and some endpoints echo; keep both out of the test output
        ob_start();

        $this->app = $this->bootApplication();
        self::db()->beginTransaction();
    }

    protected function tearDown(): void
    {
        self::db()->rollBack();
        ob_end_clean();
    }

    private function bootApplication(): Application
    {
        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
        $root = dirname(__DIR__, 2) . DS;
        defined('ROOTDIR') || define('ROOTDIR', $root);
        defined('APPDIR') || define('APPDIR', $root . 'app' . DS);
        defined('SYSTEMDIR') || define('SYSTEMDIR', $root . 'system' . DS);
        defined('PUBLICDIR') || define('PUBLICDIR', $root . 'public' . DS);

        foreach (self::environment() as $key => $value) {
            $_ENV[$key] = $value;
        }
        // Nova picks the environment from --env on the command line
        $_SERVER['argv'] = ['phpunit', '--env=testing'];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_HOST'] = 'localhost';

        $app = require APPDIR . 'Boot' . DS . 'App.php';
        // Providers otherwise boot on the first request; tests use the ORM before that (as Laravel 4's TestCase did)
        $app->boot();

        // Controllers keep the signed-in member in the native $_SESSION (Helpers\Session); no session_start() in CLI
        (new ReflectionProperty(Session::class, 'sessionStarted'))->setValue(null, true);
        $_SESSION = [];

        return $app;
    }

    /** Every variable the app reads from $_ENV, with harmless test values; no external service is reachable. */
    private static function environment(): array
    {
        $env = [
            'APP_URL' => 'http://localhost/', 'APP_EMAIL' => 'app@example.org', 'DEPLOY_ENV' => 'testing',
            'APP_PREFIX' => 'vm_', 'APP_TYPE' => 'vmast', 'APP_MAINTENANCE' => 'false', 'APP_MAINTENANCE_IPS' => '',
            'MAIN_HOST' => 'localhost', 'SOCKET_PORT' => '8001',
            'DB_HOST' => getenv('DB_HOST'), 'DB_NAME' => getenv('DB_NAME'), 'DB_USER' => getenv('DB_USER'), 'DB_PASS' => getenv('DB_PASS'),
        ];
        foreach (['MAIL_HOST', 'MAIL_FROM', 'MAIL_USER', 'MAIL_PASS', 'MAIL_APP', 'SENTRY_DSN', 'SENTRY_DSN_JS', 'LOG_ROCKET',
                  'TURNSTILE_SECRET', 'TURNSTILE_SITE_KEY', 'BC_URL', 'QA_GUIDE_URL',
                  'WACS_SERVER', 'WACS_CATALOG_URL', 'WACS_CLIENT_ID', 'WACS_CLIENT_SECRET',
                  'DCS_SERVER', 'DCS_CATALOG_URL', 'DCS_CLIENT_ID', 'DCS_CLIENT_SECRET',
                  'RABBITMQ_HOST', 'RABBITMQ_PORT', 'RABBITMQ_USER', 'RABBITMQ_PASS', 'RABBITMQ_QUEUE'] as $unused) {
            $env[$unused] = '';
        }
        return $env;
    }

    protected static function db(): Connection
    {
        return app('db')->connection();
    }

    protected function insert(string $table, array $row): int
    {
        return (int)self::db()->table($table)->insertGetId($row);
    }

    // --- Requests

    /** Signs $memberID in the way MembersController::login does. */
    protected function loginAs(int $memberID): void
    {
        $member = Member::find($memberID);
        foreach ([
            'memberID' => $member->memberID, 'userName' => $member->userName, 'firstName' => $member->firstName,
            'lastName' => $member->lastName, 'email' => $member->email, 'authToken' => $member->authToken,
            'verified' => $member->verified, 'isSuperAdmin' => $member->isSuperAdmin(), 'isGlAdmin' => $member->isGlAdmin(),
            'isProjectAdmin' => $member->isProjectAdmin(), 'isBookAdmin' => $member->isBookAdmin(),
        ] as $key => $value) {
            $_SESSION[PREFIX . $key] = $value;
        }
    }

    protected function get(string $uri, bool $ajax = false): Response
    {
        return $this->call('GET', $uri, [], $ajax);
    }

    protected function post(string $uri, array $data = [], bool $ajax = false): Response
    {
        return $this->call('POST', $uri, $data, $ajax);
    }

    protected function call(string $method, string $uri, array $data = [], bool $ajax = false): Response
    {
        // Controllers read these superglobals directly
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;
        $_POST = $method === 'POST' ? $data : [];
        $_GET = $method === 'GET' ? $data : [];
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $server = [];
        if ($ajax) {
            $server['HTTP_X_REQUESTED_WITH'] = $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        }

        ob_start();
        try {
            $response = $this->app->handle(Request::create($uri, $method, $data, [], [], $server));
        } catch (RedirectToException|HttpExceptionInterface $e) {
            // The testing environment rethrows every exception; redirects and HTTP errors become their normal responses
            $response = $this->app['exception']->handleException($e);
        } finally {
            $echoed = ob_get_clean();
        }

        // Endpoints that echo instead of returning a response
        if ($echoed !== '' && $response->getContent() === '') {
            $response->setContent($echoed);
        }
        return $response;
    }

    // --- Assertions

    protected function assertStatus(int $status, Response $response): void
    {
        $this->assertSame($status, $response->getStatusCode(), 'Location: ' . $response->headers->get('Location'));
    }

    protected function assertRedirectedTo(string $path, Response $response): void
    {
        $this->assertTrue($response->isRedirection(), 'Expected a redirect, got ' . $response->getStatusCode());
        $this->assertSame(rtrim(site_url($path), '/'), rtrim((string)$response->headers->get('Location'), '/'));
    }

    protected function assertSee(string $text, Response $response): void
    {
        $this->assertStringContainsString($text, (string)$response->getContent());
    }

    protected function json(Response $response): array
    {
        return json_decode((string)$response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
