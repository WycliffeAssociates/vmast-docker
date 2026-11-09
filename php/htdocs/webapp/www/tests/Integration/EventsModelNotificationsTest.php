<?php

namespace Tests\Integration;

use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventSteps;

/** Notifications offer a signed-in member the unassigned checks in events they take part in. */
class EventsModelNotificationsTest extends EventsModelTestCase
{
    private int $me;
    private int $colleague;
    private int $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->me = $this->member(['firstName' => 'Rose', 'lastName' => 'Green']);
        $this->colleague = $this->member(['firstName' => 'John', 'lastName' => 'Smith']);
        $this->checker = $this->member(['firstName' => 'Mary', 'lastName' => 'Jones']);
        $this->loginAs($this->me);
    }

    private function summary(array $notes, array $fields): array
    {
        return array_map(fn($n) => array_map(fn($f) => $n->$f ?? null, $fields), $notes);
    }

    private function eventOf(string $bookProject, string $bookCode = 'gen'): int
    {
        return $this->event($this->project(['bookProject' => $bookProject, 'targetLang' => 'id']), ['bookCode' => $bookCode]);
    }

    // --- Scripture

    public function testScriptureOffersOpenChecksOfColleagues(): void
    {
        $eventID = $this->eventOf('ulb');
        $this->translator($this->me, $eventID, ['step' => EventSteps::MULTI_DRAFT,
            'peerCheck' => [9 => ['memberID' => 0, 'done' => 0]]]);
        $this->translator($this->colleague, $eventID, [
            'peerCheck' => [1 => ['memberID' => 0, 'done' => 0], 2 => ['memberID' => $this->checker, 'done' => 0]],
            'kwCheck' => [3 => ['memberID' => 0, 'done' => 0]],
            'crCheck' => [
                4 => ['memberID' => 0, 'done' => 0, 'memberID2' => 0, 'done2' => 0],
                5 => ['memberID' => $this->checker, 'done' => 0, 'memberID2' => 0, 'done2' => 0],
            ],
        ]);

        $notes = $this->model->getNotifications();

        $this->assertSame([
            [EventSteps::PEER_REVIEW, 1, null, 'John', 'Genesis', 'Indonesian', EventSteps::MULTI_DRAFT],
            [EventSteps::KEYWORD_CHECK, 3, null, 'John', 'Genesis', 'Indonesian', EventSteps::MULTI_DRAFT],
            [EventSteps::CONTENT_REVIEW, 4, 1, 'John', 'Genesis', 'Indonesian', EventSteps::MULTI_DRAFT],
            [EventSteps::CONTENT_REVIEW, 4, 2, 'John', 'Genesis', 'Indonesian', EventSteps::MULTI_DRAFT],
            [EventSteps::CONTENT_REVIEW, 5, 2, 'John', 'Genesis', 'Indonesian', EventSteps::MULTI_DRAFT],
        ], $this->summary($notes, ['step', 'currentChapter', 'vChecker', 'firstName', 'bookName', 'tLang', 'myStep']),
            'taken checks and my own chapters are left out');
    }

    public function testScriptureOnlyFromMyEvents(): void
    {
        $this->translator($this->me, $this->eventOf('ulb'));
        $this->translator($this->colleague, $this->eventOf('ulb', 'exo'), ['peerCheck' => [1 => ['memberID' => 0, 'done' => 0]]]);
        $notesEvent = $this->eventOf('tn');
        $this->translator($this->me, $notesEvent);
        $this->translator($this->colleague, $notesEvent, ['peerCheck' => [1 => ['memberID' => 0, 'done' => 0]]]);

        $this->assertSame([], $this->model->getNotifications());
    }

    // --- Help tools: only for members marked as checkers in the event

    public function testHelpToolsOfferChecksToEventCheckers(): void
    {
        $eventID = $this->eventOf('tn');
        $this->translator($this->me, $eventID, ['isChecker' => 1]);
        $this->translator($this->colleague, $eventID, [
            'otherCheck' => [
                1 => ['memberID' => 0, 'done' => 0],
                2 => ['memberID' => $this->checker, 'done' => 5],
                3 => ['memberID' => $this->me, 'done' => 5],
            ],
            'peerCheck' => [2 => ['memberID' => 0, 'done' => 0], 3 => ['memberID' => 0, 'done' => 0]],
        ]);

        $this->assertSame([
            ['other', 1, 1, 'tn', 'John'],
            [EventSteps::PEER_REVIEW, 2, 2, 'tn', 'Mary'],
        ], $this->summary($this->model->getNotificationsOther(), ['step', 'currentChapter', 'peer', 'manageMode', 'firstName']),
            'the peer check of a chapter I edited myself is not offered');

        self::db()->table('translators')->where('memberID', $this->me)->update(['isChecker' => 0]);
        $this->assertSame([], $this->model->getNotificationsOther());
    }

    public function testWordsAndBcaNotificationsNameTheGroupOrWord(): void
    {
        $twEvent = $this->eventOf('tw');
        $groupID = $this->wordGroup($twEvent, ['god', 'grace', 'love']);
        $this->translator($this->me, $twEvent, ['isChecker' => 1]);
        $this->translator($this->colleague, $twEvent, ['otherCheck' => [$groupID => ['memberID' => 0, 'done' => 0]]]);

        $bcaEvent = $this->eventOf('bca', 'exo');
        $wordID = $this->word($bcaEvent, 'Abraham');
        $this->translator($this->me, $bcaEvent, ['isChecker' => 1]);
        $this->translator($this->colleague, $bcaEvent, ['otherCheck' => [$wordID => ['memberID' => 0, 'done' => 0]]]);

        $notes = $this->model->getNotificationsOther();
        usort($notes, fn($a, $b) => strcmp($a->bookProject, $b->bookProject));

        $this->assertSame([['bca', null, 'Abraham'], ['tw', 'god...love', null]], $this->summary($notes, ['bookProject', 'group', 'word']));
    }

    // --- Revision and review

    public function testRevisionOffersOpenChecksOfOtherRevisers(): void
    {
        $eventID = $this->eventOf('ulb');
        $this->checkerL2($this->me, $eventID, ['kwCheck' => [9 => ['memberID' => 0, 'done' => 0]]]);
        $this->checkerL2($this->colleague, $eventID, [
            'peerCheck' => [1 => ['memberID' => 0, 'done' => 0], 2 => ['memberID' => $this->checker, 'done' => 0]],
            'kwCheck' => [3 => ['memberID' => 0, 'done' => 0]],
            'crCheck' => [4 => ['memberID' => 0, 'done' => 0]],
        ]);

        $this->assertSame([
            [EventCheckSteps::PEER_REVIEW, 1, 'l2'],
            [EventCheckSteps::KEYWORD_CHECK, 3, 'l2'],
            [EventCheckSteps::CONTENT_REVIEW, 4, 'l2'],
        ], $this->summary($this->model->getNotificationsRevision(), ['step', 'currentChapter', 'manageMode']));
    }

    public function testReviewOffersPeerReadingsOfReviewersInPeerReview(): void
    {
        $eventID = $this->eventOf('ulb');
        $this->checkerL3($this->me, $eventID, ['step' => EventCheckSteps::PEER_REVIEW_L3, 'peerCheck' => [9 => ['memberID' => 0, 'done' => 0]]]);
        $this->checkerL3($this->colleague, $eventID, ['step' => EventCheckSteps::PEER_REVIEW_L3,
            'peerCheck' => [1 => ['memberID' => 0, 'done' => 0], 2 => ['memberID' => $this->checker, 'done' => 0]]]);
        $this->checkerL3($this->checker, $eventID, ['step' => EventCheckSteps::PEER_EDIT_L3,
            'peerCheck' => [3 => ['memberID' => 0, 'done' => 0]]]);

        $this->assertSame([[EventCheckSteps::PEER_REVIEW_L3, 1, 'l3', 'John']],
            $this->summary($this->model->getNotificationsL3(), ['step', 'currentChapter', 'manageMode', 'firstName']));
    }

    // --- SUN and radio

    public function testSunOffersOpenTheologicalAndVerseByVerseChecks(): void
    {
        $eventID = $this->eventOf('sun');
        $this->translator($this->me, $eventID, ['kwCheck' => [9 => ['memberID' => 0, 'done' => 0]]]);
        $this->translator($this->colleague, $eventID, [
            'kwCheck' => [1 => ['memberID' => 0, 'done' => 0], 2 => ['memberID' => $this->checker, 'done' => 0]],
            'crCheck' => [3 => ['memberID' => 0, 'done' => 0]],
        ]);

        $this->assertSame([[EventSteps::THEO_CHECK, 1, 'sun'], [EventSteps::CONTENT_REVIEW, 3, 'sun']],
            $this->summary($this->model->getNotificationsSun(), ['step', 'currentChapter', 'manageMode']));
    }

    public function testRadioOffersOpenPeerChecksToEventCheckers(): void
    {
        $eventID = $this->eventOf('rad');
        $this->translator($this->me, $eventID, ['isChecker' => 1, 'peerCheck' => [9 => ['memberID' => 0, 'done' => 0]]]);
        $this->translator($this->colleague, $eventID, ['peerCheck' => [1 => ['memberID' => 0, 'done' => 0], 2 => ['memberID' => $this->checker, 'done' => 0]]]);

        $this->assertSame([[EventSteps::PEER_REVIEW, 1, 'rad']],
            $this->summary($this->model->getNotificationsRadio(), ['step', 'currentChapter', 'manageMode']));

        self::db()->table('translators')->where('memberID', $this->me)->update(['isChecker' => 0]);
        $this->assertSame([], $this->model->getNotificationsRadio());
    }
}
