<?php

namespace Tests\Integration;

use App\Helpers\EventUtil;

class EventUtilTest extends DatabaseTestCase
{
    use Fixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['MAIN_HOST'] = 'vmast.test';
    }

    public function testCommentsGroupedByChapterAndChunkNewestFirst(): void
    {
        $this->language('en');
        $eventID = $this->event($this->project());
        $memberID = $this->member();
        foreach ([[1, 0, 'a'], [1, 0, 'b'], [1, 2, 'c'], [3, 0, 'd']] as [$chapter, $chunk, $text]) {
            $this->insert('comments', ['memberID' => $memberID, 'eventID' => $eventID, 'chapter' => $chapter, 'chunk' => $chunk, 'text' => $text]);
        }

        $texts = fn($grouped) => array_map(fn($chunks) => array_map(fn($list) => array_map(fn($c) => $c->text, $list), $chunks), $grouped);

        $this->assertSame([3 => [0 => ['d']], 1 => [2 => ['c'], 0 => ['b', 'a']]], $texts(EventUtil::getComments($eventID)));
        $this->assertSame([1 => [0 => ['b', 'a']]], $texts(EventUtil::getComments($eventID, 1, 0)));
        $this->assertSame([], EventUtil::getComments($eventID, 2));
    }

    private function secret(string $value, int $expire): void
    {
        self::db()->insert('INSERT INTO turn_secret (realm, value, expire) VALUES (?, ?, ?)', ['vmast.test', $value, $expire]);
    }

    private function storedSecret(): object
    {
        return self::db()->selectOne("SELECT value, expire FROM turn_secret WHERE realm = 'vmast.test'");
    }

    public function testTurnCredentialsAreAnHmacOfTheTimeLimitedUserName(): void
    {
        $this->secret('s3cret', time() + 3600);

        [$userName, $password] = EventUtil::makeTurnCredentials();

        $this->assertMatchesRegularExpression('/^\d+:vmast$/', $userName);
        $this->assertEqualsWithDelta(time() + 3600, (int)$userName, 5, 'valid for an hour');
        $this->assertSame(base64_encode(hash_hmac('sha1', $userName, 's3cret', true)), $password);
        $this->assertSame('s3cret', $this->storedSecret()->value);
    }

    public function testExpiredTurnSecretIsRotatedForAMonth(): void
    {
        $this->secret('old', time() - 1);

        [$userName, $password] = EventUtil::makeTurnCredentials();

        $stored = $this->storedSecret();
        $this->assertNotSame('old', $stored->value);
        $this->assertSame(22, strlen($stored->value));
        $this->assertEqualsWithDelta(time() + 30 * 24 * 3600, $stored->expire, 5);
        $this->assertSame(base64_encode(hash_hmac('sha1', $userName, $stored->value, true)), $password, 'signed with the new secret');
    }

    public function testNoTurnSecretGivesAnEmptyPassword(): void
    {
        $this->assertSame('', EventUtil::makeTurnCredentials()[1]);
    }
}
