<?php

namespace Tests\Unit;

use App\Data\NotificationMapper;
use PHPUnit\Framework\TestCase;

class NotificationMapperTest extends TestCase
{
    /** A notification model with its relations, as the ORM loads it. */
    private function model(string $bookProject, array $extra = []): object
    {
        return (object)array_merge([
            'noteID' => 77,
            'eventID' => 5,
            'fromMemberID' => 10,
            'manageMode' => 'l2',
            'step' => 'peer-review',
            'currentChapter' => 3,
            'type' => 'ready',
            'event' => (object)[
                'project' => (object)[
                    'bookProject' => $bookProject,
                    'sourceBible' => 'ulb',
                    'targetLanguage' => (object)['langName' => 'English'],
                ],
                'bookInfo' => (object)['name' => 'Genesis'],
                'inputMode' => 'normal',
            ],
            'fromMember' => (object)['firstName' => 'John', 'lastName' => 'Smith'],
        ], $extra);
    }

    public function testMapsNotificationWithItsEventProjectAndSender(): void
    {
        $this->assertEquals((object)[
            'noteID' => 77,
            'eventID' => 5,
            'memberID' => 10,
            'manageMode' => 'l2',
            'step' => 'peer-review',
            'currentChapter' => 3,
            'type' => 'ready',
            'bookProject' => 'ulb',
            'tLang' => 'English',
            'sourceBible' => 'ulb',
            'bookName' => 'Genesis',
            'inputMode' => 'normal',
            'firstName' => 'John',
            'lastName' => 'Smith',
        ], NotificationMapper::toData($this->model('ulb')));
    }

    public function testBcaAddsTheWord(): void
    {
        $data = NotificationMapper::toData($this->model('bca', ['word' => (object)['word' => 'Abraham']]));

        $this->assertSame('Abraham', $data->word);
        $this->assertObjectNotHasProperty('group', $data);
    }

    public function testTwAddsTheGroupsFirstAndLastWord(): void
    {
        $model = $this->model('tw', ['wordGroup' => (object)['words' => json_encode(['god', 'grace', 'love'])]]);

        $this->assertSame('god...love', NotificationMapper::toData($model)->group);
    }

    public function testTwGroupOfOneWord(): void
    {
        $model = $this->model('tw', ['wordGroup' => (object)['words' => json_encode(['god'])]]);

        $this->assertSame('god...god', NotificationMapper::toData($model)->group);
    }
}
