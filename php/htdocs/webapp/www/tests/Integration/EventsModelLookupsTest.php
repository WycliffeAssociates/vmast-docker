<?php

namespace Tests\Integration;

use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventSteps;

/** EventsModel lookups and the plain create/update/delete helpers. */
class EventsModelLookupsTest extends EventsModelTestCase
{
    private int $translator;
    private int $glID;
    private int $projectID;
    private int $eventID;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translator = $this->member(['userName' => 'jsmith', 'firstName' => 'John', 'lastName' => 'Smith']);
        $this->glID = $this->insert('gateway_languages', ['gwLang' => 'en']);
        $this->projectID = $this->project(['glID' => $this->glID, 'targetLang' => 'id']);
        $this->eventID = $this->event($this->projectID);
    }

    public function testGetProjectWithTargetLanguage(): void
    {
        $this->project(['glID' => $this->glID, 'targetLang' => 'en', 'bookProject' => 'tn']);

        $byWhere = $this->model->getProject(['projects.projectID', 'languages.langName'], ['projects.bookProject', 'ulb']);
        $byConditions = $this->model->getProject(['projects.projectID'], [['projects.targetLang', 'en'], ['projects.bookProject', 'tn']]);

        $this->assertSame([[$this->projectID, 'Indonesian']], array_map(fn($p) => [$p->projectID, $p->langName], $byWhere));
        $this->assertCount(1, $byConditions);
    }

    public function testGetEventByIdOrByProjectAndBook(): void
    {
        $byID = $this->model->getEvent($this->eventID);
        $byBook = $this->model->getEvent(null, $this->projectID, 'gen');

        $this->assertSame([[$this->eventID, 'Genesis', 50, 'ulb', 'id']],
            array_map(fn($e) => [$e->eventID, $e->name, $e->chaptersNum, $e->bookProject, $e->targetLang], $byID));
        $this->assertSame($this->eventID, $byBook[0]->eventID);
        $this->assertSame([], $this->model->getEvent(null, $this->projectID, 'exo'));
    }

    public function testGetEventCountsDistinctMembers(): void
    {
        $this->translator($this->translator, $this->eventID);
        $this->translator($this->member(), $this->eventID);
        $this->checkerL2($this->translator, $this->eventID);
        $this->checkerL3($this->translator, $this->eventID);
        $this->checkerL3($this->member(), $this->eventID);

        $event = $this->model->getEvent($this->eventID, null, null, true)[0];

        $this->assertSame([2, 1, 2], [$event->translators, $event->checkers_l2, $event->checkers_l3]);
    }

    public function testEventMemberInfo(): void
    {
        $this->translator($this->translator, $this->eventID, ['isChecker' => 1]);
        $this->checkerL2($this->translator, $this->eventID);
        $this->checkerL2($this->member(), $this->eventID);
        $this->checkerL3($this->member(), $this->eventID);

        $info = $this->model->getEventMemberInfo($this->eventID, $this->translator);

        $this->assertSame([[$this->translator, 'ulb', 1, $this->translator, null]],
            array_map(fn($i) => [$i->translator, $i->bookProject, $i->isChecker, $i->l2checker, $i->l3checker], $info));
    }

    public function testMembersOfRevisionEventByUserName(): void
    {
        $this->checkerL2($this->member(['userName' => 'zed']), $this->eventID);
        $this->checkerL2($this->member(['userName' => 'amy']), $this->eventID);
        $this->checkerL2($this->member(['userName' => 'bob']), $this->event($this->projectID, ['bookCode' => 'exo']));

        $members = $this->model->getMembersForRevisionEvent($this->eventID);

        $this->assertSame(['amy', 'zed'], array_column($members, 'userName'));
        $this->assertIsObject(self::db()->selectOne('SELECT 1 AS one'), 'fetch mode is restored');
    }

    public function testMembersForProjectTypes(): void
    {
        $this->translator($this->translator, $this->eventID);
        $notesEvent = $this->event($this->project(['glID' => $this->glID, 'bookProject' => 'tn']));
        $this->translator($this->member(), $notesEvent);

        $this->assertSame([$this->eventID], array_map(fn($t) => $t->eventID, $this->model->getMembersForProject(['ulb', 'udb'])));
        $this->assertCount(2, $this->model->getMembersForProject(['ulb', 'tn']));
    }

    public function testBooksOfTranslators(): void
    {
        $trID = $this->translator($this->translator, $this->eventID);
        $this->chapter($trID, 2);
        $this->chapter($trID, 1, ['done' => 1]);
        $twEvent = $this->event($this->project(['glID' => $this->glID, 'bookProject' => 'tw']), ['bookCode' => 'exo']);
        $groupID = $this->wordGroup($twEvent, ['god']);
        $this->chapter($this->translator($this->translator, $twEvent), $groupID);

        $books = $this->model->getBooksOfTranslators();

        $this->assertSame(
            [['jsmith', 'Genesis', 1, 1, null], ['jsmith', 'Genesis', 2, 0, null], ['jsmith', 'Exodus', $groupID, 0, '["god"]']],
            array_map(fn($b) => [$b->userName, $b->name, $b->chapter, (int)$b->done, $b->words], $books)
        );
    }

    public function testAllLanguagesWithGatewayLanguage(): void
    {
        self::db()->table('languages')->where('langID', 'en')->update(['isGW' => 1]);

        $languages = fn(...$args) => array_map(fn($l) => [$l->langID, $l->glID], $this->model->getAllLanguages(...$args));

        $this->assertSame([['en', $this->glID], ['id', null]], $languages());
        $this->assertSame([['en', $this->glID]], $languages(true));
        $this->assertSame([['id', null]], $languages(false));
        $this->assertSame([['id', null]], $languages(null, ['id']));
    }

    public function testCreateUpdateAndDeleteHelpers(): void
    {
        $projectID = $this->model->createProject(['glID' => $this->glID, 'gwLang' => 'en', 'targetLang' => 'en', 'sourceLangID' => 'en']);
        $this->assertSame(1, $this->model->updateProject(['bookProject' => 'tq'], ['projectID' => $projectID]));

        $eventID = $this->model->createEvent(['projectID' => $projectID, 'bookCode' => 'exo']);
        $this->assertSame(1, $this->model->updateEvent(['state' => 'translated'], ['eventID' => $eventID]));
        $this->assertSame('translated', $this->model->getEvent($eventID)[0]->state);

        $trID = $this->model->addTranslator(['memberID' => $this->translator, 'eventID' => $eventID, 'currentChapter' => 0, 'currentChunk' => 0,
            'verbCheck' => '', 'peerCheck' => '', 'kwCheck' => '', 'crCheck' => '', 'otherCheck' => '']);
        $l2ID = $this->model->addL2Checker(['memberID' => $this->translator, 'eventID' => $eventID, 'currentChapter' => 0,
            'peerCheck' => '', 'kwCheck' => '', 'crCheck' => '']);
        $l3ID = $this->model->addL3Checker(['memberID' => $this->translator, 'eventID' => $eventID, 'currentChapter' => 0, 'peerCheck' => '']);
        $this->assertGreaterThan(0, $trID);

        $this->model->updateL2Checker(['step' => EventCheckSteps::SELF_CHECK], ['l2chID' => $l2ID]);
        $this->model->updateL3Checker(['step' => EventCheckSteps::PEER_REVIEW_L3], ['l3chID' => $l3ID]);
        $this->assertSame(EventCheckSteps::SELF_CHECK, self::db()->table('checkers_l2')->where('l2chID', $l2ID)->first()->step);
        $this->assertSame(EventCheckSteps::PEER_REVIEW_L3, self::db()->table('checkers_l3')->where('l3chID', $l3ID)->first()->step);

        $groupID = $this->model->createWordGroup(['eventID' => $eventID, 'words' => '["god"]']);
        $this->assertSame([$groupID], array_map(fn($g) => $g->groupID, $this->model->getWordGroups(['eventID' => $eventID])));
        $this->assertSame(1, $this->model->deleteWordGroup(['groupID' => $groupID]));

        $wordID = $this->word($eventID, 'Abraham');
        $this->assertSame('Abraham', $this->model->getWord(['wordID' => $wordID])[0]->word);

        // Deleting an event cascades to its translators and checkers
        $this->assertSame(1, $this->model->deleteEvent(['eventID' => $eventID]));
        $this->assertSame(0, self::db()->table('translators')->where('eventID', $eventID)->count());
    }
}
