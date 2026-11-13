<?php

namespace Tests\Unit;

use App\Data\Notification;
use App\Domain\RenderNotifications;
use Helpers\Constants\EventSteps;
use Helpers\Constants\NotificationType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\FakeLanguage;

class NotificationTest extends TestCase
{
    protected function setUp(): void
    {
        FakeLanguage::install();
    }

    protected function tearDown(): void
    {
        FakeLanguage::uninstall();
    }

    /** Notification data as NotificationMapper builds it: a level 1 ULB peer check of chapter 3. */
    private static function data(array $overrides = []): object
    {
        return (object)array_merge([
            'noteID' => null,
            'eventID' => 5,
            'memberID' => 10,
            'manageMode' => null,
            'step' => EventSteps::PEER_REVIEW,
            'currentChapter' => 3,
            'type' => null,
            'bookProject' => 'ulb',
            'tLang' => 'English',
            'sourceBible' => 'ulb',
            'bookName' => 'Genesis',
            'inputMode' => null,
            'firstName' => 'John',
            'lastName' => 'Smith',
        ], $overrides);
    }

    private function note(array $overrides = []): Notification
    {
        return new Notification(self::data($overrides));
    }

    // --- Checker links

    public static function urls(): array
    {
        return [
            'stored notification' => [['noteID' => 77], '/events/notifications/77/apply'],
            'scripture, first verse checker by default' => [[], '/events/checker/5/10/3/peer-review/v1/apply'],
            'scripture, second verse checker ready' => [
                ['vChecker' => 2, 'type' => NotificationType::READY],
                '/events/checker/5/10/3/peer-review/v2/notified',
            ],
            'scripture, approved' => [['type' => NotificationType::DONE], '/events/checker/5/10/3/peer-review/v1/approved'],
            'notes' => [
                ['manageMode' => 'tn', 'bookProject' => 'tn', 'step' => 'other', 'currentChapter' => 0],
                '/events/checker-tn/5/10/other/0/apply',
            ],
            'sun' => [
                ['manageMode' => 'sun', 'bookProject' => 'sun', 'step' => EventSteps::THEO_CHECK],
                '/events/checker-sun/5/10/theo-check/3/apply',
            ],
            'radio' => [['manageMode' => 'rad', 'bookProject' => 'rad'], '/events/checker-rad/5/10/peer-review/3/apply'],
            'scripture revision' => [['manageMode' => 'l2'], '/events/checker/5/10/peer-review/3/apply'],
            'scripture review' => [['manageMode' => 'l3', 'step' => 'peer-review-l3'], '/events/checker/5/10/peer-review-l3/3/apply'],
        ];
    }

    #[DataProvider('urls')]
    public function testUrl(array $overrides, string $url): void
    {
        $this->assertSame($url, $this->note($overrides)->getUrl());
    }

    public static function drafterUrls(): array
    {
        return [
            'scripture' => [[], '/events/translator/5/3'],
            'notes' => [['manageMode' => 'tn', 'bookProject' => 'tn'], '/events/checker-tn/5/10/3'],
            'scripture revision' => [['manageMode' => 'l2'], '/events/checker-revision/5/3'],
            'sun review' => [['manageMode' => 'l3', 'bookProject' => 'sun'], '/events/checker-sun-review/5/'],
        ];
    }

    #[DataProvider('drafterUrls')]
    public function testDrafterUrl(array $overrides, string $url): void
    {
        $this->assertSame($url, $this->note($overrides)->getDrafterUrl());
    }

    // --- Demo links must match the demo pages in DemoController

    public static function demoUrls(): array
    {
        return [
            'scripture' => [[], '/events/demo/peer_review_checker'],
            'scripture by speech' => [['inputMode' => 'speech-to-text'], '/events/demo-speech-to-text/peer_review_checker'],
            'scripture revision' => [['manageMode' => 'l2'], '/events/demo-revision/peer_review'],
            'sun revision' => [['manageMode' => 'l2', 'bookProject' => 'sun'], '/events/demo-sun-revision/theo_check'],
            'scripture review' => [['manageMode' => 'l3', 'step' => 'peer-review-l3'], '/events/demo-review/peer_review_l3_checker'],
            'notes review' => [
                ['manageMode' => 'l3', 'bookProject' => 'tn', 'step' => 'peer-review-l3'],
                '/events/demo-tn-review/peer_review_l3_checker',
            ],
            'notes' => [['manageMode' => 'tn', 'bookProject' => 'tn', 'step' => 'other'], '/events/demo-tn/pray_chk'],
            'odb sun' => [
                ['manageMode' => 'sun', 'bookProject' => 'sun', 'sourceBible' => 'odb', 'step' => EventSteps::THEO_CHECK],
                '/events/demo-sun-odb/theo_check_checker',
            ],
            'radio' => [['manageMode' => 'rad', 'bookProject' => 'rad'], '/events/demo-rad/peer_review'],
        ];
    }

    #[DataProvider('demoUrls')]
    public function testDemoUrl(array $overrides, string $url): void
    {
        $this->assertSame($url, $this->note($overrides)->getDemoUrl());
    }

    // --- Parts of the notification text

    public function testNameModeAnchorAndType(): void
    {
        $note = $this->note();

        $this->assertSame('John S.', $note->getName());
        $this->assertSame('peer-review_checker', $note->getMode());
        $this->assertSame('check:5:10', $note->getAnchor());
        $this->assertSame(NotificationType::STARTED, $note->getType());
        $this->assertSame('', $this->note(['eventID' => null])->getAnchor());
    }

    public static function steps(): array
    {
        return [
            'translated step' => [[], 'peer-review'],
            'help tool peer review' => [['bookProject' => 'tn'], 'peer-review_tn'],
            'odb' => [['sourceBible' => 'odb'], 'peer-review_odb'],
            'sun revision' => [['manageMode' => 'l2', 'bookProject' => 'sun'], 'peer-review_sun'],
            'help tool level 2 check' => [['bookProject' => 'tn', 'step' => 'other'], 'level(0=2)'],
        ];
    }

    #[DataProvider('steps')]
    public function testStep(array $overrides, string $step): void
    {
        $this->assertSame($step, $this->note($overrides)->getStep());
    }

    public function testChapter(): void
    {
        $this->assertSame('3', $this->note()->getChapter());
        $this->assertSame('intro', $this->note(['currentChapter' => 0])->getChapter());
        $this->assertSame('god...love', $this->note(['group' => 'god...love'])->getChapter());
        $this->assertSame('Abraham', $this->note(['word' => 'Abraham'])->getChapter());
    }

    public function testProjectAndLevel(): void
    {
        $this->assertSame('ulb', $this->note()->getProject());
        $this->assertSame('odb', $this->note(['bookProject' => 'sun', 'sourceBible' => 'odb'])->getProject());
        $this->assertSame('', $this->note()->getLevel());
        $this->assertSame('(l2)', $this->note(['manageMode' => 'l2'])->getLevel());
        $this->assertSame('(l3)', $this->note(['manageMode' => 'l3'])->getLevel());
    }

    public function testText(): void
    {
        $this->assertSame(
            'checker_apply(name=<b>John S.</b>) apply_for_chapter(step=<b>peer-review</b>,book=<b>Genesis</b>,'
            . 'chapter=<b>3</b>,language=<b>English</b>,project=<b>ulb,level=</b>)',
            $this->note()->getText()
        );
    }

    public static function textKeys(): array
    {
        return [
            'ready' => [['type' => NotificationType::READY], 'checker_ready(', 'apply_for_chapter(', ''],
            'approved' => [['type' => NotificationType::DONE], 'checker_approved(', 'apply_for_chapter(', ''],
            'word group' => [['bookProject' => 'tw', 'group' => 'a...b'], 'checker_apply(', 'apply_for_group(', ' (#2)'],
            'word' => [['bookProject' => 'bca', 'word' => 'a'], 'checker_apply(', 'apply_for_word(', ' (#2)'],
            'help tool level 2 check' => [['bookProject' => 'tn', 'step' => 'other'], 'checker_apply(', 'apply_for_chapter(', ' (#1)'],
            'help tool review has no tail' => [['bookProject' => 'tn', 'manageMode' => 'l3'], 'checker_apply(', 'apply_for_chapter(', ''],
            'content review, first checker' => [
                ['step' => EventSteps::CONTENT_REVIEW, 'vChecker' => 1], 'checker_apply(', 'apply_for_chapter(', ' (l1_v1_checker)',
            ],
            'content review, second checker' => [
                ['step' => EventSteps::CONTENT_REVIEW, 'vChecker' => 2], 'checker_apply(', 'apply_for_chapter(', ' (l1_v2_checker)',
            ],
        ];
    }

    #[DataProvider('textKeys')]
    public function testTextKeysAndTail(array $overrides, string $start, string $chapterKey, string $tail): void
    {
        $text = $this->note($overrides)->getText();

        $this->assertStringStartsWith($start, $text);
        $this->assertStringContainsString(") $chapterKey", $text);
        // the chapter text ends with the level and its closing </b>; the tail, if any, follows
        $this->assertStringEndsWith('</b>)' . $tail, $text);
    }

    // --- RenderNotifications wraps each notification in a link

    public function testRender(): void
    {
        $note = $this->note();
        $rendered = (new RenderNotifications([self::data(), self::data(['noteID' => 77])]))->render();

        $this->assertCount(2, $rendered);
        $this->assertSame(
            "<a class='notifa' href='/events/checker/5/10/3/peer-review/v1/apply' data-anchor='check:5:10'"
            . " data-type='started' target='_blank'><li class='peer-review_checker'>{$note->getText()}</li></a>",
            $rendered[0]
        );
        $this->assertStringContainsString("href='/events/notifications/77/apply'", $rendered[1]);
    }

    public function testRenderDemo(): void
    {
        $render = new RenderNotifications();
        $render->setNotifications([self::data()]);

        $this->assertSame(
            ["<a class='notifa' href='/events/demo/peer_review_checker' target='_blank'>"
            . "<li class='peer-review_checker'>{$this->note()->getText()}</li></a>"],
            $render->renderDemo()
        );
        $this->assertSame([], (new RenderNotifications())->render());
    }
}
