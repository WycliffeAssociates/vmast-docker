<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 05 Feb 2016
 * Time: 19:57
 */

namespace App\Models;

use App\Models\ORM\Word;
use App\Models\ORM\WordGroup;
use App\Repositories\Event\IEventRepository;
use Database\Model;
use Database\ORM\Collection;
use DB;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventSteps;
use Helpers\Constants\ResourcesCheckSteps;
use Helpers\Constants\TnCheckSteps;
use Helpers\Session;
use PDO;


class EventsModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'eventID';
    protected $eventRepo = null;

    public function __construct(IEventRepository $eventRepo)
    {
        parent::__construct();
        $this->eventRepo = $eventRepo;
    }

    /**
     * Get project
     * @param array $select An array of fields
     * @param array $where Single/Multidimentional array with where params (field, operator, value, logical)
     * @return array|static[]
     */
    public function getProject(array $select, array $where)
    {
        $builder = $this->db->table("projects");

        foreach ($where as $item) {
            if (is_array($item)) {
                call_user_func_array(array($builder, "where"), $item);
            } else {
                call_user_func_array(array($builder, "where"), $where);
                break;
            }
        }

        return $builder
            ->leftJoin("languages", "languages.langID", "=", "projects.targetLang")
            ->select($select)->get();
    }


    /**
     * Get all events of a member or specific event
     * @param $memberID
     * @param null $eventID
     * @param null $chapter
     * @param bool $includeCheckers
     * @param bool $includeFinished
     * @param bool $includeNone
     * @return array
     */
    public function getMemberEvents(
        $memberID,
        $eventID = null,
        $chapter = null,
        $includeCheckers = false,
        $includeFinished = true,
        $includeNone = true
    ) {
        $sql = "SELECT translators.trID, " .
            "translators.memberID AS myMemberID, translators.step, " .
            "translators.currentChunk, translators.currentChapter, " .
            "translators.verbCheck, translators.peerCheck, " .
            "translators.kwCheck, translators.crCheck, " .
            "translators.otherCheck, translators.isChecker, " .
            "word_groups.words, words.word, " .
            "(SELECT COUNT(*) FROM " . PREFIX . "translators AS all_trs WHERE all_trs.eventID = translators.eventID ) AS currTrs, " .
            "evnt.eventID, evnt.state, evnt.bookCode, evnt.dateFrom, evnt.inputMode, " .
            "evnt.dateTo, " .
            "projects.*, " .
            "t_lang.langName as tLang, chapters.chunks, " .
            "t_lang.direction as tLangDir, projects.resLangID, res_lang.direction as resLangDir, " .
            "s_lang.langName as sLang, s_lang.direction as sLangDir, " .
            "book_info.name, book_info.sort, book_info.chaptersNum  " .
            "FROM " . PREFIX . "translators AS translators " .
            "LEFT JOIN " . PREFIX . "word_groups AS word_groups ON word_groups.groupID = translators.currentChapter AND translators.eventID = word_groups.eventID " .
            "LEFT JOIN " . PREFIX . "words AS words ON words.wordID = translators.currentChapter AND translators.eventID = words.eventID " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON translators.eventID = chapters.eventID AND translators.currentChapter = chapters.chapter ".
            "LEFT JOIN " . PREFIX . "events AS evnt ON translators.eventID = evnt.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON evnt.projectID = projects.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS res_lang ON projects.resLangID = res_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE translators.eventID !='' " .
            (!is_null($memberID) ? " AND translators.memberID = :memberID " : " ") .
            (!is_null($eventID) ? " AND translators.eventID=:eventID " : " ") .
            "ORDER BY tLang, projects.bookProject, book_info.sort";

        $prepare = array();
        if (!is_null($memberID))
            $prepare[":memberID"] = $memberID;

        if (!is_null($eventID))
            $prepare[":eventID"] = $eventID;

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            if (empty($eventAdmins)) {
                $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            }
            $event->admins = $eventAdmins;

            $checkingSteps = [
                EventSteps::PEER_REVIEW,
                EventSteps::KEYWORD_CHECK,
                EventSteps::CONTENT_REVIEW,
                EventSteps::FINAL_REVIEW
            ];
            $excludedSteps = [];
            if (!$includeNone) {
                $excludedSteps[] = EventSteps::NONE;
            }
            if (!$includeFinished) {
                $excludedSteps[] = EventSteps::FINISHED;
            }
            $checkingSteps = array_merge($checkingSteps, $excludedSteps);
            $inTranslation = !in_array($event->step, $checkingSteps);

            if (in_array($event->bookProject, ["ulb","udb"])) {
                if ($inTranslation && !isset($chapter)) {
                    $event->checkerID = 0;
                    $filtered[] = $event;
                }
            } else {
                if (!in_array($event->step, $excludedSteps)) {
                    $event->checkerID = 0;
                    $filtered[] = $event;
                    continue;
                }
            }

            if (in_array($event->bookProject, ["ulb","udb"])) {
                $peerCheck = (array)json_decode($event->peerCheck, true);
                $kwCheck = (array)json_decode($event->kwCheck, true);
                $crCheck = (array)json_decode($event->crCheck, true);
                $otherCheck = (array)json_decode($event->otherCheck, true);

                foreach ($peerCheck as $chap => $data) {
                    if ($data["done"] < 2 && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                        $ev = $this->modifiedCheckerEvent($event, $chap, $data, EventSteps::PEER_REVIEW);
                        $filtered[] = $ev;
                    }
                }

                foreach ($kwCheck as $chap => $data) {
                    if ($data["done"] < 2 && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                        $ev = $this->modifiedCheckerEvent($event, $chap, $data, EventSteps::KEYWORD_CHECK);
                        $filtered[] = $ev;
                    }
                }

                foreach ($crCheck as $chap => $data) {
                    if ($data["done"] < 2 && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                        $ev = $this->modifiedCheckerEvent($event, $chap, $data, EventSteps::CONTENT_REVIEW);
                        $filtered[] = $ev;
                    }
                }

                foreach ($otherCheck as $chap => $data) {
                    if ($data["done"] == 0 && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                        $ev = clone $event;
                        $ev->step = EventSteps::FINAL_REVIEW;
                        $ev->currentChapter = $chap;
                        $ev->checkerID = 0;

                        $chapters = $this->getChapters($event->eventID, $event->myMemberID, $chapter); // Should be one
                        $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";
                        $ev->chunks = $chunks;

                        $filtered[] = $ev;
                    }
                }
            }
        }

        return array_map(function($item) {
            if ($item->step == EventSteps::PRAY) {
                $nextChapter = $this->getNextChapter($item->eventID, $item->myMemberID);
                if (!empty($nextChapter)) {
                    $item->currentChapter = $nextChapter[0]->chapter;
                    if ($item->bookProject == "tw") {
                        $wordGroup = WordGroup::find($item->currentChapter);
                        if ($wordGroup) {
                            $item->words = $wordGroup->words;
                        }
                    } elseif ($item->bookProject == "bca") {
                        $word = Word::find($item->currentChapter);
                        if ($word) {
                            $item->word = $word->word;
                        }
                    }
                }
            }
            return $item;
        }, $filtered);
    }

    private function modifiedCheckerEvent($event, $chapter, $data, $step, $manageMode = "l1") {
        $ev = clone $event;

        $checkers = [];
        $checkerID = 0;
        $ev->step = $step;
        $ev->checkDone = false;

        if ($data["memberID"] != 0) {
            $memberModel = new MembersModel();
            $member = $memberModel->getMember([
                "firstName",
                "lastName"
            ], ["memberID", $data["memberID"]]);
            if (!empty($member)) {
                $checkers[] = [
                    "name" => $member[0]->firstName . " " . mb_substr($member[0]->lastName, 0, 1).".",
                    "id" => $data["memberID"]
                ];
                $checkerID = $data["memberID"];
            }
            $ev->checkDone = $data["done"] == 1;
        }
        if (isset($data["memberID2"]) && $data["memberID2"] != 0) {
            $memberModel = new MembersModel();
            $member = $memberModel->getMember([
                "firstName",
                "lastName"
            ], ["memberID", $data["memberID2"]]);
            if (!empty($member)) {
                $checkers[] = [
                    "name" => $member[0]->firstName . " " . mb_substr($member[0]->lastName, 0, 1).".",
                    "id" => $data["memberID2"]
                ];
            }
            $ev->checkDone2 = $data["done2"] == 1;
        }

        $ev->currentChapter = $chapter;
        $ev->checkerID = $checkerID;
        $ev->checkers = $checkers;

        $chapters = $this->getChapters($event->eventID, $event->myMemberID, $chapter, $manageMode); // Should be one
        $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";
        $ev->chunks = $chunks;

        return $ev;
    }

    /**
     * Get translator information
     * @param $memberID Checker member ID
     * @param null $eventID event ID
     * @param null $trMemberID Translator member ID
     * @return array
     */
    public function getMemberEventsForChecker($memberID, $eventID = null, $trMemberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($trMemberID)
            $prepare[":trMemberID"] = $trMemberID;

        $sql = "SELECT trs.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS bookName, book_info.sort, " .
            "projects.*, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, evnt.inputMode, " .
            "chapters.chunks, projects.projectID " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON trs.eventID = chapters.eventID AND trs.currentChapter = chapters.chapter " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE trs.trID > 0 " .
            "AND (projects.bookProject IN ('ulb','udb') " .
            ($eventID ? "AND trs.eventID = :eventID " : " ") .
            ($trMemberID ? "AND trs.memberID = :trMemberID " : " ") . ") ".
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);

        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            if (empty($eventAdmins)) {
                $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            }
            $event->admins = $eventAdmins;

            $peerCheck = (array)json_decode($event->peerCheck, true);
            $kwCheck = (array)json_decode($event->kwCheck, true);
            $crCheck = (array)json_decode($event->crCheck, true);

            foreach ($peerCheck as $chap => $data) {
                // Exclude translator's events
                if ($event->memberID == $memberID) continue;
                // Exclude finished events
                if ($data["done"] > 0) continue;
                // Exclude other checkers events
                if ($data["memberID"] != $memberID) continue;
                // Filter to secific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventSteps::PEER_REVIEW;
                $ev->currentChapter = $chap;
                $ev->checkerID = $data["memberID"];
                $ev->chunks = $chunks;
                $filtered[] = $ev;
            }

            foreach ($kwCheck as $chap => $data) {
                // Exclude translator's events
                if ($event->memberID == $memberID) continue;
                // Exclude finished events
                if ($data["done"] > 0) continue;
                // Exclude other checkers events
                if ($data["memberID"] != $memberID) continue;
                // Filter to secific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventSteps::KEYWORD_CHECK;
                $ev->currentChapter = $chap;
                $ev->checkerID = $data["memberID"];
                $ev->chunks = $chunks;
                $filtered[] = $ev;
            }

            foreach ($crCheck as $chap => $data) {
                // Exclude translator's events
                if ($event->memberID == $memberID) continue;

                $checkerID = 0;
                $vChecker = 0;
                if (isset($data["memberID"]) && $data["memberID"] == $memberID) {
                    $checkerID = $data["memberID"];
                    $vChecker = 1;

                    // Exclude finished events
                    if (isset($data["done"]) && $data["done"] > 0) continue;

                } elseif (isset($data["memberID2"]) && $data["memberID2"] == $memberID) {
                    $checkerID = $data["memberID2"];
                    $vChecker = 2;

                    // Exclude finished events
                    if (isset($data["done2"]) && $data["done2"] > 0) continue;
                }

                // Exclude other checkers events
                if ($checkerID == 0) continue;
                // Filter to specific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventSteps::CONTENT_REVIEW;
                $ev->currentChapter = $chap;
                $ev->checkerID = $checkerID;
                $ev->vChecker = $vChecker;
                $ev->chunks = $chunks;
                $filtered[] = $ev;
            }
        }

        return $filtered;
    }

    /**
     * Get Notes checker event/s
     * @param $memberID int Notes Checker member ID
     * @param null $eventID event ID
     * @param null $chkMemberID Notes translator member ID
     * @param $chapter
     * @param $type string (all, edit, check)
     * @return array
     */
    public function getMemberEventsForNotes(int $memberID, $eventID = null, $chkMemberID = null, $chapter = null, $type = "all")
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($chkMemberID)
            $prepare[":chkMemberID"] = $chkMemberID;

        $sql = "SELECT trs.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, " .
            "evnt.dateFrom, evnt.dateTo, evnt.state, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, " .
            "projects.targetLang, projects.resLangID, res_lang.direction as resLangDir, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS res_lang ON projects.resLangID = res_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE projects.bookProject = 'tn' " .
            ($eventID ? "AND trs.eventID = :eventID " : " ") .
            ($chkMemberID ? "AND trs.memberID = :chkMemberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            // Checker event
            if (empty($eventAdmins)) $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            $event->admins = $eventAdmins;
            $event->manageMode = $event->bookProject;

            $otherCheck = (array)json_decode($event->otherCheck, true);
            $peerCheck = (array)json_decode($event->peerCheck, true);

            if ($type == "all" || $type == "edit") {
                foreach ($otherCheck as $chap => $data) {
                    if (!isset($chapter) || $chapter == $chap) {
                        if ($data["memberID"] == $memberID && $data["done"] != 6) {
                            $ev = clone $event;

                            $checkerFName = null;
                            $checkerLName = null;
                            $checkerID = 0;
                            $ev->step = EventSteps::PRAY;
                            $ev->checkDone = false;

                            if (isset($peerCheck[$chap]) && $peerCheck[$chap]["memberID"] != 0) {
                                $memberModel = new MembersModel();
                                $member = $memberModel->getMember([
                                    "firstName",
                                    "lastName"
                                ], ["memberID", $peerCheck[$chap]["memberID"]]);
                                if (!empty($member)) {
                                    $checkerFName = $member[0]->firstName;
                                    $checkerLName = $member[0]->lastName;
                                    $checkerID = $peerCheck[$chap]["memberID"];
                                }

                                $ev->checkDone = $peerCheck[$chap]["done"] > 0;
                            }

                            switch ($data["done"]) {
                                case TnCheckSteps::CONSUME:
                                    $ev->step = EventSteps::CONSUME;
                                    break;
                                case TnCheckSteps::HIGHLIGHT:
                                    $ev->step = EventSteps::HIGHLIGHT;
                                    break;
                                case TnCheckSteps::SELF_CHECK:
                                    $ev->step = EventSteps::SELF_CHECK;
                                    break;
                                case TnCheckSteps::KEYWORD_CHECK:
                                    $ev->step = EventSteps::KEYWORD_CHECK;
                                    break;
                                case TnCheckSteps::PEER_REVIEW:
                                    $ev->step = EventSteps::PEER_REVIEW;
                                    break;
                            }

                            $ev->currentChapter = $chap;
                            $ev->peer = 1;
                            $ev->myMemberID = 0;
                            $ev->myChkMemberID = $memberID;
                            $ev->checkerFName = $checkerFName;
                            $ev->checkerLName = $checkerLName;
                            $ev->checkerID = $checkerID;
                            $ev->isContinue = true; // Means not owner of chapter
                            $ev->isCheckerPage = true;
                            $filtered[] = $ev;
                        }
                    }
                }
            }

            // Peer check event
            if ($type == "all" || $type == "check") {
                foreach ($peerCheck as $chap => $data) {
                    if (!isset($chapter) || $chapter == $chap) {
                        if ($data["memberID"] == $memberID && $data["done"] == 0) {
                            $ev = clone $event;
                            $checkerFName = null;
                            $checkerLName = null;
                            $checkerID = 0;

                            $memberModel = new MembersModel();
                            $member = $memberModel->getMember([
                                "firstName",
                                "lastName"
                            ], ["memberID", $otherCheck[$chap]["memberID"]]);
                            if (!empty($member)) {
                                $checkerFName = $member[0]->firstName;
                                $checkerLName = $member[0]->lastName;
                                $checkerID = $otherCheck[$chap]["memberID"];
                            }

                            $ev->step = EventSteps::PEER_REVIEW;
                            $ev->currentChapter = $chap;
                            $ev->peer = 2;
                            $ev->myMemberID = $memberID;
                            $ev->myChkMemberID = $memberID;
                            $ev->checkerFName = $checkerFName;
                            $ev->checkerLName = $checkerLName;
                            $ev->checkerID = $checkerID;
                            $ev->isContinue = true;
                            $ev->isCheckerPage = true;
                            $filtered[] = $ev;
                        }
                    }
                }
            }
        }


        return $filtered;
    }

    /**
     * Get Questions checker event/s
     * @param $memberID Notes Checker member ID
     * @param null $eventID event ID
     * @param null $chkMemberID Notes translator member ID
     * @param $chapter
     * @param $type string (all, edit, check)
     * @return array
     */
    public function getMemberEventsForOther($memberID, $eventID = null, $chkMemberID = null, $chapter = null, $type = "all")
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($chkMemberID)
            $prepare[":chkMemberID"] = $chkMemberID;

        $sql = "SELECT trs.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, " .
            "evnt.dateFrom, evnt.dateTo, evnt.state, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, " .
            "projects.targetLang, projects.resLangID, res_lang.direction as resLangDir, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID, " .
            "word_groups.words, words.word " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS res_lang ON projects.resLangID = res_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "LEFT JOIN " . PREFIX . "word_groups AS word_groups ON trs.currentChapter = word_groups.groupID AND trs.eventID = word_groups.eventID " .
            "LEFT JOIN " . PREFIX . "words AS words ON trs.currentChapter = words.wordID AND trs.eventID = words.eventID " .
            "WHERE projects.bookProject IN ('tq','tw','obs','bc','bca') " .
            ($eventID ? "AND trs.eventID = :eventID " : " ") .
            ($chkMemberID ? "AND trs.memberID = :chkMemberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            // Checker event
            if (empty($eventAdmins)) $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            $event->admins = $eventAdmins;
            $event->manageMode = $event->bookProject;

            $otherCheck = (array)json_decode($event->otherCheck, true);
            $peerCheck = (array)json_decode($event->peerCheck, true);

            if ($type == "all" || $type == "edit") {
                foreach ($otherCheck as $chap => $data) {
                    if (!isset($chapter) || $chapter == $chap) {
                        if ($data["memberID"] == $memberID && $data["done"] != ResourcesCheckSteps::FINISHED) {
                            if ($event->bookProject == "tw") {
                                $group = $this->getWordGroups([
                                    "groupID" => $chap,
                                    "eventID" => $event->eventID
                                ]);

                                $event->words = $group[0]->words;
                            } elseif ($event->bookProject == "bca") {
                                $word = $this->getWord([
                                    "wordID" => $chap,
                                    "eventID" => $event->eventID
                                ]);
                                $event->word = $word[0]->word;
                            }

                            $ev = clone $event;

                            $checkerFName = null;
                            $checkerLName = null;
                            $checkerID = 0;
                            $ev->step = EventSteps::PRAY;
                            $ev->checkDone = false;

                            if (isset($peerCheck[$chap]) && $peerCheck[$chap]["memberID"] != 0) {
                                $memberModel = new MembersModel();
                                $member = $memberModel->getMember([
                                    "firstName",
                                    "lastName"
                                ], ["memberID", $peerCheck[$chap]["memberID"]]);
                                if (!empty($member)) {
                                    $checkerFName = $member[0]->firstName;
                                    $checkerLName = $member[0]->lastName;
                                    $checkerID = $peerCheck[$chap]["memberID"];
                                }

                                $ev->checkDone = $peerCheck[$chap]["done"] > 0;
                            }

                            switch ($data["done"]) {
                                case ResourcesCheckSteps::KEYWORD_CHECK:
                                    $ev->step = EventSteps::KEYWORD_CHECK;
                                    break;
                                case ResourcesCheckSteps::PEER_REVIEW:
                                    $ev->step = EventSteps::PEER_REVIEW;
                                    break;
                            }

                            $ev->currentChapter = $chap;
                            $ev->peer = 1;
                            $ev->myMemberID = 0;
                            $ev->myChkMemberID = $memberID;
                            $ev->checkerFName = $checkerFName;
                            $ev->checkerLName = $checkerLName;
                            $ev->checkerID = $checkerID;
                            $ev->isContinue = true; // Means not owner of chapter
                            $ev->isCheckerPage = true;
                            $filtered[] = $ev;
                        }
                    }
                }
            }

            if ($type == "all" || $type == "check") {
                // Peer check event
                foreach ($peerCheck as $chap => $data) {
                    if (!isset($chapter) || $chapter == $chap) {
                        if ($data["memberID"] == $memberID && $data["done"] == 0) {
                            if ($event->bookProject == "tw") {
                                $group = $this->getWordGroups([
                                    "groupID" => $chap,
                                    "eventID" => $event->eventID
                                ]);
                                $event->words = $group[0]->words;
                            } elseif ($event->bookProject == "bca") {
                                $word = $this->getWord([
                                    "wordID" => $chap,
                                    "eventID" => $event->eventID
                                ]);
                                $event->word = $word[0]->word;
                            }

                            $ev = clone $event;
                            $checkerFName = null;
                            $checkerLName = null;
                            $checkerID = 0;

                            $memberModel = new MembersModel();
                            $member = $memberModel->getMember([
                                "firstName",
                                "lastName"
                            ], ["memberID", $otherCheck[$chap]["memberID"]]);
                            if (!empty($member)) {
                                $checkerFName = $member[0]->firstName;
                                $checkerLName = $member[0]->lastName;
                                $checkerID = $otherCheck[$chap]["memberID"];
                            }

                            $ev->step = EventSteps::PEER_REVIEW;
                            $ev->currentChapter = $chap;
                            $ev->peer = 2;
                            $ev->myMemberID = $memberID;
                            $ev->myChkMemberID = $memberID;
                            $ev->checkerFName = $checkerFName;
                            $ev->checkerLName = $checkerLName;
                            $ev->checkerID = $checkerID;
                            $ev->isContinue = true;
                            $ev->isCheckerPage = true;
                            $filtered[] = $ev;
                        }
                    }
                }
            }
        }

        return $filtered;
    }

    /**
     * Get tW groups
     * @param $where
     * @return mixed
     */
    public function getWordGroups($where)
    {
        return $this->db->table("word_groups")
            ->where($where)
            ->orderBy("groupID")
            ->get();
    }

    /**
     * Get bca word
     * @param $where
     * @return mixed
     */
    public function getWord($where)
    {
        return $this->db->table("words")
            ->where($where)
            ->orderBy("wordID")
            ->get();
    }

    /**
     * Get Radio checker event/s
     * @param $memberID Notes Checker member ID
     * @param null $eventID event ID
     * @param null $chkMemberID Notes translator member ID
     * @param $chapter
     * @return array
     */
    public function getMemberEventsForRadio($memberID, $eventID = null, $chkMemberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($chkMemberID)
            $prepare[":chkMemberID"] = $chkMemberID;

        $sql = "SELECT trs.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, " .
            "evnt.dateFrom, evnt.dateTo, evnt.state, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, " .
            "projects.targetLang, projects.resLangID, res_lang.direction as resLangDir, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS res_lang ON projects.resLangID = res_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE projects.bookProject = 'rad' " .
            ($eventID ? "AND trs.eventID = :eventID " : " ") .
            ($chkMemberID ? "AND trs.memberID = :chkMemberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            // Checker event
            if (empty($eventAdmins)) $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            $event->admins = $eventAdmins;
            $peerCheck = (array)json_decode($event->peerCheck, true);
            foreach ($peerCheck as $chap => $data) {
                if (!isset($chapter) || $chapter == $chap) {
                    if ($data["memberID"] == $memberID && $data["done"] != 1) {
                        $ev = clone $event;

                        $ev->step = EventSteps::PEER_REVIEW;
                        $ev->currentChapter = $chap;
                        $ev->myMemberID = 0;
                        $ev->checkerID = 0;
                        $ev->checkDone = 0;
                        $ev->myChkMemberID = $memberID;
                        $ev->isContinue = true; // Means not owner of chapter
                        $ev->isCheckerPage = true;
                        $filtered[] = $ev;
                    }
                }
            }
        }

        return $filtered;
    }


    /**
     * Get all events of a revision checker or specific event
     * @param $memberID
     * @param null $eventID
     * @return array
     */
    public function getCheckerRevisionEvents($memberID, $eventID = null)
    {
        $sql = "SELECT checkers.*, " .
            "(SELECT COUNT(*) FROM ".PREFIX."checkers_l2 AS all_chkrs WHERE all_chkrs.eventID = checkers.eventID ) AS currChkrs, " .
            "evnt.eventID, evnt.state, evnt.bookCode, evnt.dateFrom, evnt.inputMode, " .
            "evnt.dateTo, " .
            "projects.projectID, projects.bookProject, " .
            "projects.sourceLangID, projects.gwLang, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, " .
            "projects.targetLang, projects.glID, " .
            "projects.sourceBible, t_lang.langName as tLang, chapters.chunks, " .
            "t_lang.direction as tLangDir, projects.resLangID, res_lang.direction as resLangDir, " .
            "s_lang.langName as sLang, s_lang.direction as sLangDir, " .
            "book_info.name, book_info.sort, book_info.chaptersNum " .
            "FROM " . PREFIX . "checkers_l2 AS checkers " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON checkers.eventID = chapters.eventID AND checkers.currentChapter = chapters.chapter " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON checkers.eventID = evnt.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON evnt.projectID = projects.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS res_lang ON projects.resLangID = res_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE checkers.eventID !='' " .
            (!is_null($memberID) ? " AND checkers.memberID = :memberID " : " ") .
            (!is_null($eventID) ? " AND checkers.eventID=:eventID " : " ") .
            "ORDER BY tLang, projects.bookProject, book_info.sort";

        $prepare = array();
        if (!is_null($memberID))
            $prepare[":memberID"] = $memberID;

        if (!is_null($eventID))
            $prepare[":eventID"] = $eventID;

        return $this->db->select($sql, $prepare);
    }


    /**
     * Get all events of a member or specific event
     * @param $memberID
     * @param null $eventID
     * @param null $chapter
     * @param bool $includeCheckers
     * @param bool $includeNone
     * @return array
     */
    public function getRevisionMemberEvents(
        $memberID,
        $eventID = null,
        $chapter = null,
        $includeCheckers = false,
        $includeNone = true
    ) {
        $sql = "SELECT chks.*, chks.memberID AS myMemberID, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "evnt.dateFrom, evnt.dateTo, evnt.revisionMode, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, projects.bcLangID, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.targetLang, projects.resLangID, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID, " .
            "chapters.chunks " .
            "FROM " . PREFIX . "checkers_l2 AS chks " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON chks.eventID = chapters.eventID AND chks.currentChapter = chapters.chapter ".
            "LEFT JOIN " . PREFIX . "members AS members ON chks.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE chks.l2chID != 0 " .
            ($memberID ? " AND chks.memberID = :memberID " : " ") .
            ($eventID ? "AND chks.eventID = :eventID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $prepare = [];
        if (!is_null($memberID))
            $prepare[":memberID"] = $memberID;

        if (!is_null($eventID))
            $prepare[":eventID"] = $eventID;

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            if (empty($eventAdmins)) {
                $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            }
            $event->admins = $eventAdmins;

            $checkingSteps = [
                EventCheckSteps::PEER_REVIEW,
                EventCheckSteps::KEYWORD_CHECK,
                EventCheckSteps::CONTENT_REVIEW
            ];
            $excludedSteps = [];
            if (!$includeNone) {
                $excludedSteps[] = EventCheckSteps::NONE;
            }
            $checkingSteps = array_merge($checkingSteps, $excludedSteps);
            $inChecking = !in_array($event->step, $checkingSteps);

            if ($inChecking && !isset($chapter)) {
                $event->checkerID = 0;
                $filtered[] = $event;
            }

            $peerCheck = (array)json_decode($event->peerCheck, true);
            $kwCheck = (array)json_decode($event->kwCheck, true);
            $crCheck = (array)json_decode($event->crCheck, true);

            foreach ($peerCheck as $chap => $data) {
                if ($event->bookProject == "sun" && $data["memberID"] != $memberID) continue;

                $done = $event->bookProject != "sun" ? 2 : 1;

                if ($data["done"] < $done && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                    $ev = $this->modifiedCheckerEvent($event, $chap, $data, EventCheckSteps::PEER_REVIEW, "l2");
                    $filtered[] = $ev;
                }
            }

            foreach ($kwCheck as $chap => $data) {
                if ($data["done"] < 2 && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                    $ev = $this->modifiedCheckerEvent($event, $chap, $data, EventCheckSteps::KEYWORD_CHECK, "l2");
                    $filtered[] = $ev;
                }
            }

            foreach ($crCheck as $chap => $data) {
                if ($data["done"] < 2 && $includeCheckers && (!isset($chapter) || $chapter == $chap)) {
                    $ev = $this->modifiedCheckerEvent($event, $chap, $data, EventCheckSteps::CONTENT_REVIEW, "l2");
                    $filtered[] = $ev;
                }
            }
        }

        return array_map(function($item) {
            if ($item->step == EventCheckSteps::PRAY) {
                $nextChapter = $this->getNextChapter($item->eventID, $item->myMemberID, "l2");
                if (!empty($nextChapter)) {
                    $item->currentChapter = $nextChapter[0]->chapter;
                }
            }
            return $item;
        }, $filtered);
    }


    /**
     * Get approving revision checker event/s
     * @param int $memberID Approver Checker member ID
     * @param null $eventID
     * @param null $chkMemberID 1st Checker member ID
     * @param null $chapter
     * @return array
     */
    public function getMemberEventsForRevisionChecker($memberID, $eventID = null, $chkMemberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($chkMemberID)
            $prepare[":chkMemberID"] = $chkMemberID;

        $sql = "SELECT chks.*, chks.memberID AS checker_l2, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "evnt.dateFrom, evnt.dateTo, evnt.revisionMode, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, projects.bcLangID, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.targetLang, projects.resLangID, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID, " .
            "chapters.chunks " .
            "FROM " . PREFIX . "checkers_l2 AS chks " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON chks.eventID = chapters.eventID AND chks.currentChapter = chapters.chapter ".
            "LEFT JOIN " . PREFIX . "members AS members ON chks.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE chks.l2chID != 0 AND projects.bookProject != 'sun' " .
            ($eventID ? "AND chks.eventID = :eventID " : " ") .
            ($chkMemberID ? "AND chks.memberID = :chkMemberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            if (empty($eventAdmins[$event->eventID])) {
                $eventAdmins[$event->eventID] = $this->eventRepo->get($event->eventID)->admins;
            }
            $event->admins = $eventAdmins[$event->eventID];
            $event->manageMode = "l2";

            $peerCheck = (array)json_decode($event->peerCheck, true);
            foreach ($peerCheck as $chap => $data) {
                // Exclude finished events
                if ($data["done"] > 0) continue;
                // Exclude other checkers events
                if ($data["memberID"] != $memberID) continue;
                // Filter to specific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventCheckSteps::PEER_REVIEW;
                $ev->currentChapter = $chap;
                $ev->checkerID = $data["memberID"];
                $ev->chunks = $chunks;
                $ev->checkDone = false;
                $filtered[] = $ev;
            }

            $kwCheck = (array)json_decode($event->kwCheck, true);
            foreach ($kwCheck as $chap => $data) {
                // Exclude translator's events
                if ($event->memberID == $memberID) continue;
                // Exclude finished events
                if ($data["done"] > 0) continue;
                // Exclude other checkers events
                if ($data["memberID"] != $memberID) continue;
                // Filter to specific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventSteps::KEYWORD_CHECK;
                $ev->currentChapter = $chap;
                $ev->checkerID = $data["memberID"];
                $ev->chunks = $chunks;
                $filtered[] = $ev;
            }

            $crCheck = (array)json_decode($event->crCheck, true);
            foreach ($crCheck as $chap => $data) {
                // Exclude translator's events
                if ($event->memberID == $memberID) continue;
                // Exclude finished events
                if ($data["done"] > 0) continue;
                // Exclude other checkers events
                if ($data["memberID"] != $memberID) continue;
                // Filter to specific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventSteps::CONTENT_REVIEW;
                $ev->currentChapter = $chap;
                $ev->checkerID = $data["memberID"];
                $ev->chunks = $chunks;
                $filtered[] = $ev;
            }
        }

        return $filtered;
    }


    /**
     * Get SUN revision checker event/s
     * @param int $memberID Checker member ID
     * @param null $eventID
     * @param null $chkMemberID 1st Checker member ID
     * @param null $chapter
     * @return array
     */
    public function getMemberEventsForSunRevisionChecker($memberID, $eventID = null, $chkMemberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($chkMemberID)
            $prepare[":chkMemberID"] = $chkMemberID;

        $sql = "SELECT chks.*, chks.memberID AS checker_l2, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "evnt.dateFrom, evnt.dateTo, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, projects.bcLangID, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.targetLang, projects.resLangID, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID, " .
            "chapters.chunks " .
            "FROM " . PREFIX . "checkers_l2 AS chks " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON chks.eventID = chapters.eventID AND chks.currentChapter = chapters.chapter ".
            "LEFT JOIN " . PREFIX . "members AS members ON chks.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE chks.l2chID != 0 AND projects.bookProject = 'sun' " .
            ($eventID ? "AND chks.eventID = :eventID " : " ") .
            ($chkMemberID ? "AND chks.memberID = :chkMemberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            if (empty($eventAdmins[$event->eventID])) {
                $eventAdmins[$event->eventID] = $this->eventRepo->get($event->eventID)->admins;
            }
            $event->admins = $eventAdmins[$event->eventID];

            $peerCheck = (array)json_decode($event->peerCheck, true);
            foreach ($peerCheck as $chap => $data) {
                // Exclude finished events
                if ($data["done"] > 0) continue;
                // Exclude other checkers events
                if ($data["memberID"] != $memberID) continue;
                // Filter to specific chapter
                if ($chapter && $chapter != $chap) continue;

                $chapters = $this->getChapters($event->eventID, $event->memberID, $chapter); // Should be one
                $chunks = !empty($chapters) ? $chapters[0]["chunks"] : "";

                $ev = clone $event;
                $ev->step = EventCheckSteps::PEER_REVIEW;
                $ev->currentChapter = $chap;
                $ev->checkerID = $data["memberID"];
                $ev->chunks = $chunks;
                $ev->checkDone = false;
                $filtered[] = $ev;
            }
        }

        return array_map(function($item) {
            if ($item->step == EventCheckSteps::PRAY) {
                $nextChapter = $this->getNextChapter($item->eventID, $item->myMemberID, "l2");
                if (!empty($nextChapter)) {
                    $item->currentChapter = $nextChapter[0]->chapter;
                }
            }
            return $item;
        }, $filtered);
    }

    /**
     * Get all events of a L3 checker or specific event
     * @param $memberID
     * @param null $eventID
     * @return array
     */
    public function getCheckerL3Events($memberID, $eventID = null)
    {
        $sql = "SELECT checkers.l3chID, checkers.memberID, checkers.step, " .
            "checkers.currentChapter, checkers.peerCheck, " .
            "(SELECT COUNT(*) FROM ".PREFIX."checkers_l3 AS all_chkrs WHERE all_chkrs.eventID = checkers.eventID ) AS currChkrs, " .
            "evnt.eventID, evnt.state, evnt.bookCode, evnt.dateFrom, evnt.inputMode, " .
            "evnt.dateTo, " .
            "projects.projectID, projects.bookProject, " .
            "projects.sourceLangID, projects.gwLang, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, " .
            "projects.targetLang, projects.glID, " .
            "projects.sourceBible, t_lang.langName as tLang, chapters.chunks, " .
            "t_lang.direction as tLangDir, projects.resLangID, res_lang.direction as resLangDir, " .
            "s_lang.langName as sLang, s_lang.direction as sLangDir, " .
            "book_info.name, book_info.sort, book_info.chaptersNum " .
            "FROM " . PREFIX . "checkers_l3 AS checkers " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON checkers.eventID = chapters.eventID AND checkers.currentChapter = chapters.chapter " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON checkers.eventID = evnt.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON evnt.projectID = projects.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS res_lang ON projects.resLangID = res_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE checkers.eventID !='' " .
            (!is_null($memberID) ? " AND checkers.memberID = :memberID " : " ") .
            (!is_null($eventID) ? " AND checkers.eventID=:eventID " : " ") .
            "ORDER BY tLang, projects.bookProject, book_info.sort";

        $prepare = array();
        if (!is_null($memberID))
            $prepare[":memberID"] = $memberID;

        if (!is_null($eventID))
            $prepare[":eventID"] = $eventID;

        return $this->db->select($sql, $prepare);
    }


    /**
     * Get L3 checker event/s
     * @param $memberID 1st Checker member ID
     * @param null $eventID
     * @param null $chkMemberID Peer checker
     * @param null $chapter
     * @return array
     */
    public function getMemberEventsForCheckerL3($memberID, $eventID = null, $chkMemberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($chkMemberID)
            $prepare[":chkMemberID"] = $chkMemberID;

        $sql = "SELECT chks.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "evnt.dateFrom, evnt.dateTo, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, projects.bcLangID, " .
            "projects.sourceBible, projects.gwLang, projects.glID, " .
            "projects.targetLang, projects.resLangID, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID, " .
            "chapters.chunks " .
            "FROM " . PREFIX . "checkers_l3 AS chks " .
            "LEFT JOIN " . PREFIX . "chapters AS chapters ON chks.eventID = chapters.eventID AND chks.currentChapter = chapters.chapter ".
            "LEFT JOIN " . PREFIX . "members AS members ON chks.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE chks.l3chID != 0 " .
            ($eventID ? "AND chks.eventID = :eventID " : " ") .
            ($chkMemberID ? "AND chks.memberID = :chkMemberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = new Collection();

        foreach ($events as $event) {
            // First Checker events
            if ($eventAdmins->isEmpty()) {
                $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            }

            $event->admins = $eventAdmins;
            $event->manageMode = "l3";

            if ($event->memberID == $memberID
                && $event->step != EventCheckSteps::NONE
                && ($chapter == null || $chapter == $event->currentChapter)) {
                $filtered[] = $event;
            }

            // Peer Check events
            $peerCheck = (array)json_decode($event->peerCheck, true);
            foreach ($peerCheck as $chap => $data) {
                if (!isset($chapter) || $chapter == $chap) {
                    if ($data["memberID"] == $memberID && $data["done"] != 2) {
                        $ev = clone $event;

                        $memberModel = new MembersModel();
                        $member = $memberModel->getMember([
                            "firstName",
                            "lastName"
                        ], ["memberID", $ev->memberID]);
                        $checkerFName = $member[0]->firstName;
                        $checkerLName = $member[0]->lastName;

                        $ev->peerStep = $ev->step;
                        $ev->step = $data["done"] == 0 ?
                            EventCheckSteps::PEER_REVIEW_L3 :
                            EventCheckSteps::PEER_EDIT_L3;
                        $ev->currentChapter = $chap;
                        $ev->l3memberID = $ev->memberID;
                        $ev->myMemberID = 0;
                        $ev->myChkMemberID = $memberID;
                        $ev->chkMemberID = $ev->l3memberID;
                        $ev->checkerFName = $checkerFName;
                        $ev->checkerLName = $checkerLName;
                        $ev->isContinue = true; // Means not owner of chapter
                        $filtered[] = $ev;
                    }
                }
            }
        }

        return array_map(function($item) {
            if ($item->step == EventCheckSteps::PRAY) {
                $nextChapter = $this->getNextChapter($item->eventID, $item->memberID, "l3");
                if (!empty($nextChapter)) {
                    $item->currentChapter = $nextChapter[0]->chapter;
                }
            }
            return $item;
        }, $filtered);
    }


    /**
     * Get SUN checker event/s
     * @param $checkerID Checker member ID
     * @param null $eventID event ID
     * @param null $memberID Translator member ID
     * @return array
     */
    public function getMemberEventsForCheckerSun($checkerID, $eventID = null, $memberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($memberID)
            $prepare[":memberID"] = $memberID;

        $sql = "SELECT trs.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "evnt.dateFrom, evnt.dateTo, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.targetLang, projects.resLangID, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE projects.bookProject = 'sun' AND trs.kwCheck != '' " .
            ($eventID ? "AND trs.eventID = :eventID " : " ") .
            ($memberID ? "AND trs.memberID = :memberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];
        $eventAdmins = [];

        foreach ($events as $event) {
            // Theo Check events
            if (empty($eventAdmins)) $eventAdmins = $this->eventRepo->get($event->eventID)->admins;
            $event->admins = $eventAdmins;
            $kwCheck = (array)json_decode($event->kwCheck, true);
            foreach ($kwCheck as $chap => $data) {
                if (!isset($chapter) || $chapter == $chap) {
                    if ($data["memberID"] == $checkerID && $data["done"] == 0) {
                        $ev = clone $event;

                        $ev->step = EventSteps::THEO_CHECK;
                        $ev->currentChapter = $chap;
                        $ev->myMemberID = 0;
                        $ev->myChkMemberID = $checkerID;
                        $ev->isContinue = true; // Means not owner of chapter
                        $filtered[] = $ev;
                    }
                }
            }

            // Verse-by-verse Check events
            $crCheck = (array)json_decode($event->crCheck, true);
            foreach ($crCheck as $chap => $data) {
                if (!isset($chapter) || $chapter == $chap) {
                    $doneStatus = $event->sourceBible == "odb" ? 1 : 2;
                    if ($data["memberID"] == $checkerID && $data["done"] != $doneStatus) {
                        $ev = clone $event;

                        $ev->step = $data["done"] == 0 ?
                            EventSteps::CONTENT_REVIEW :
                            EventSteps::FINAL_REVIEW;
                        $ev->currentChapter = $chap;
                        $ev->myMemberID = 0;
                        $ev->myChkMemberID = $checkerID;
                        $ev->isContinue = true; // Means not owner of chapter
                        $filtered[] = $ev;
                    }
                }
            }
        }

        return $filtered;
    }


    /**
     * Get SUN checker event/s
     * @param $checkerID SUN Checker member ID
     * @param null $eventID event ID
     * @param null $memberID SUN translator member ID
     * @return array
     */
    public function getMemberEventsForSun($checkerID, $eventID = null, $memberID = null, $chapter = null)
    {
        $prepare = [];
        if ($eventID)
            $prepare[":eventID"] = $eventID;
        if ($memberID)
            $prepare[":memberID"] = $memberID;

        $sql = "SELECT trs.*, members.userName, members.firstName, " .
            "members.lastName, evnt.bookCode, evnt.state, " .
            "evnt.dateFrom, evnt.dateTo, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, " .
            "book_info.name AS name, book_info.sort, " .
            "projects.sourceLangID, projects.bookProject, " .
            "projects.tnLangID, projects.tqLangID, projects.twLangID, " .
            "projects.sourceBible, projects.gwLang, " .
            "projects.targetLang, projects.resLangID, " .
            "t_lang.direction as tLangDir, s_lang.direction as sLangDir, " .
            "book_info.chaptersNum, projects.projectID " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS evnt ON evnt.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = evnt.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON evnt.bookCode = book_info.code " .
            "WHERE projects.bookProject = 'sun' " .
            ($eventID ? "AND trs.eventID = :eventID " : " ") .
            ($memberID ? "AND trs.memberID = :memberID " : " ") .
            "ORDER BY tLang, book_info.sort";

        $events = $this->db->select($sql, $prepare);
        $filtered = [];

        foreach ($events as $event) {
            // translation events
            if ($event->memberID == $checkerID
                && $event->step != EventCheckSteps::NONE
                && ($chapter == null || $chapter == $event->currentChapter)) {
                $filtered[] = $event;
            }

            // Theo Check events
            $kwCheck = (array)json_decode($event->kwCheck, true);
            foreach ($kwCheck as $chap => $data) {
                if (!isset($chapter) || $chapter == $chap) {
                    if ($data["memberID"] == $checkerID && $data["done"] == 0) {
                        $ev = clone $event;

                        $ev->step = EventSteps::THEO_CHECK;
                        $ev->currentChapter = $chap;
                        $ev->myMemberID = 0;
                        $ev->myChkMemberID = $checkerID;
                        $ev->isContinue = true; // Means not owner of chapter
                        $filtered[] = $ev;
                    }
                }
            }

            // Verse-by-verse Check events
            $crCheck = (array)json_decode($event->crCheck, true);
            foreach ($crCheck as $chap => $data) {
                if (!isset($chapter) || $chapter == $chap) {
                    if ($data["memberID"] == $checkerID && $data["done"] != 2) {
                        $ev = clone $event;

                        $ev->step = $data["done"] == 0 ?
                            EventSteps::CONTENT_REVIEW :
                            EventSteps::FINAL_REVIEW;
                        $ev->currentChapter = $chap;
                        $ev->myMemberID = 0;
                        $ev->myChkMemberID = $checkerID;
                        $ev->isContinue = true; // Means not owner of chapter
                        $ev->checkDone = false;
                        $filtered[] = $ev;
                    }
                }
            }
        }

        return $filtered;
    }

    public function getMembersForProject($projectTypes)
    {
        return $this->db->table("translators")
            ->leftJoin("events", "events.eventID", "=", "translators.eventID")
            ->leftJoin("projects", "projects.projectID", "=", "events.projectID")
            ->whereIn("projects.bookProject", $projectTypes)
            ->orderBy("events.eventID")
            ->get();
    }

    public function getMembersForRevisionEvent($eventID)
    {
        $this->db->setFetchMode(PDO::FETCH_ASSOC);
        $builder = $this->db->table("checkers_l2")
            ->select("checkers_l2.*", "members.userName", "members.firstName", "members.lastName")
            ->leftJoin("members", "checkers_l2.memberID", "=", "members.memberID")
            ->where("checkers_l2.eventID", $eventID);

        $res = $builder->orderBy("members.userName")->get();
        $this->db->setFetchMode(PDO::FETCH_CLASS);

        return $res;
    }

    /**
     * Get all assigned chapters of event of a translator
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @param $manageMode
     * @return array|static[]
     */
    public function getChapters($eventID, $memberID = null, $chapter = null, $manageMode = "l1")
    {
        $this->db->setFetchMode(PDO::FETCH_ASSOC);

        $builder = $this->db->table("chapters");

        if ($manageMode == "l2") {
            $builder->leftJoin("checkers_l2", function ($join) {
                $join->on("chapters.eventID", "=", "checkers_l2.eventID")
                    ->on("chapters.l2memberID", "=", "checkers_l2.memberID");
            });
            if ($memberID !== null)
                $builder->where(["chapters.l2memberID" => $memberID]);
        } else if ($manageMode == "l3") {
            $builder->leftJoin("checkers_l3", function ($join) {
                $join->on("chapters.eventID", "=", "checkers_l3.eventID")
                    ->on("chapters.l3memberID", "=", "checkers_l3.memberID");
            });
            if ($memberID !== null)
                $builder->where(["chapters.l3memberID" => $memberID]);
        } else if ($manageMode != null) {
            $builder->leftJoin("translators", function ($join) {
                $join->on("chapters.eventID", "=", "translators.eventID")
                    ->on("chapters.memberID", "=", "translators.memberID");
            });
            if ($memberID !== null)
                $builder->where(["chapters.memberID" => $memberID]);
        } else {
            if ($memberID !== null)
                $builder->where(["chapters.memberID" => $memberID]);
        }

        if ($chapter !== null)
            $builder->where(["chapters.chapter" => $chapter]);

        $builder->where(["chapters.eventID" => $eventID])
            ->orderBy("chapters.chapter");

        $res = $builder->get();

        $this->db->setFetchMode(PDO::FETCH_CLASS);

        return $res;
    }

    /**
     * Get notifications for assigned events
     * @return array
     */
    public function getNotifications()
    {
        $myMemberID = Session::get("memberID");
        $sql = "SELECT trs.*, " .
            "members.userName, members.firstName, members.lastName, " .
            "events.bookCode, projects.sourceBible, projects.bookProject, mytrs.step as myStep, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, book_info.name AS bookName " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS events ON events.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "translators as mytrs ON mytrs.memberID = :memberID AND mytrs.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = events.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON events.bookCode = book_info.code " .
            "WHERE trs.eventID IN(SELECT eventID FROM " . PREFIX . "translators WHERE memberID = :memberID) " .
            "AND projects.bookProject IN ('ulb', 'udb')";

        $prepare = [
            ":memberID" => $myMemberID
        ];

        $notifications = $this->db->select($sql, $prepare);
        $notifs = [];

        foreach ($notifications as $notification) {
            $peerCheck = (array)json_decode($notification->peerCheck, true);
            $kwCheck = (array)json_decode($notification->kwCheck, true);
            $crCheck = (array)json_decode($notification->crCheck, true);

            foreach ($peerCheck as $chapter => $data) {
                // Exclude taken chapters
                if ($data["memberID"] > 0) continue;

                // Exclude member that is translator
                if ($notification->memberID == $myMemberID) continue;

                $note = clone $notification;
                $note->currentChapter = $chapter;
                $note->step = EventSteps::PEER_REVIEW;
                $note->checkerID = 0;
                $notifs[] = $note;
            }

            foreach ($kwCheck as $chapter => $data) {
                // Exclude taken chapters
                if ($data["memberID"] > 0) continue;

                // Exclude member that is translator
                if ($notification->memberID == $myMemberID) continue;

                $note = clone $notification;
                $note->currentChapter = $chapter;
                $note->step = EventSteps::KEYWORD_CHECK;
                $note->checkerID = 0;
                $notifs[] = $note;
            }

            foreach ($crCheck as $chapter => $data) {
                // Exclude member that is translator
                if ($notification->memberID == $myMemberID) continue;

                // First checker
                // Exclude taken chapters
                if (isset($data["memberID"]) && $data["memberID"] <= 0) {
                    $note = clone $notification;
                    $note->currentChapter = $chapter;
                    $note->step = EventSteps::CONTENT_REVIEW;
                    $note->checkerID = 0;
                    $note->vChecker = 1;
                    $notifs[] = $note;
                }

                if (isset($data["memberID2"]) && $data["memberID2"] <= 0) {
                    $note = clone $notification;
                    $note->currentChapter = $chapter;
                    $note->step = EventSteps::CONTENT_REVIEW;
                    $note->checkerID = 0;
                    $note->vChecker = 2;
                    $notifs[] = $note;
                }
            }
        }

        return $notifs;
    }

    /**
     * Get notifications from tN, tQ and tW events
     * @return array
     */
    public function getNotificationsOther()
    {
        $sql = "SELECT trs.*, " .
            "members.userName, members.firstName, members.lastName, " .
            "events.bookCode, projects.sourceBible, projects.bookProject, mytrs.step as myStep, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, book_info.name AS bookName " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS events ON events.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "translators AS mytrs ON mytrs.memberID = :memberID AND mytrs.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = events.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON events.bookCode = book_info.code " .
            "WHERE trs.eventID IN(SELECT eventID FROM " . PREFIX . "translators WHERE memberID = :memberID AND isChecker=1) " .
            "AND projects.bookProject IN ('tq','tw','tn','obs','bc','bca')";

        $prepare = [
            ":memberID" => Session::get("memberID")
        ];

        $questionsNotifications = $this->db->select($sql, $prepare);
        $notifs = [];

        foreach ($questionsNotifications as $notification) {
            $otherCheck = (array)json_decode($notification->otherCheck, true);
            $peerCheck = (array)json_decode($notification->peerCheck, true);

            foreach ($otherCheck as $chapter => $data) {
                // Exclude taken chapters
                if ($data["memberID"] > 0) continue;

                // Exclude member that is translator
                if ($notification->memberID == Session::get("memberID")) continue;

                if ($notification->bookProject == "tw") {
                    $group = $this->getWordGroups([
                        "groupID" => $chapter,
                        "eventID" => $notification->eventID]);

                    $words = (array)json_decode($group[0]->words, true);
                    $notification->group = $words[0] . "..." . $words[sizeof($words) - 1];
                } elseif ($notification->bookProject == "bca") {
                    $e = $this->eventRepo->get($notification->eventID);
                    $word = $e->words->filter(function ($w) use ($chapter) {
                        return $w->wordID == $chapter;
                    })->first()->word;
                    $notification->word = $word;
                }

                $note = clone $notification;
                $note->currentChapter = $chapter;
                $note->step = "other";
                $note->manageMode = $notification->bookProject;
                $note->peer = 1;
                $notifs[] = $note;
            }

            foreach ($peerCheck as $chapter => $data) {
                // Exclude taken chapters
                if ($data["memberID"] > 0) continue;

                // Exclude member that is already in otherCheck
                if ($otherCheck[$chapter]["memberID"] == Session::get("memberID")) continue;

                if ($notification->bookProject == "tw") {
                    $group = $this->getWordGroups([
                        "groupID" => $chapter,
                        "eventID" => $notification->eventID]);

                    $words = (array)json_decode($group[0]->words, true);
                    $notification->group = $words[0] . "..." . $words[sizeof($words) - 1];
                } elseif ($notification->bookProject == "bca") {
                    $e = $this->eventRepo->get($notification->eventID);
                    $word = $e->words->filter(function ($w) use ($chapter) {
                        return $w->wordID == $chapter;
                    })->first()->word;
                    $notification->word = $word;
                }

                $note = clone $notification;

                $memberModel = new MembersModel();
                $member = $memberModel->getMember([
                    "firstName",
                    "lastName"
                ], ["memberID", $otherCheck[$chapter]["memberID"]]);
                if (!empty($member)) {
                    $note->firstName = $member[0]->firstName;
                    $note->lastName = $member[0]->lastName;
                }

                $note->currentChapter = $chapter;
                $note->step = EventSteps::PEER_REVIEW;
                $note->manageMode = $notification->bookProject;
                $note->peer = 2;
                $notifs[] = $note;
            }
        }

        return $notifs;
    }

    /**
     * Get notifications for revision events
     * @return array
     */
    public function getNotificationsRevision()
    {
        $sql = "SELECT chks.*, " .
            "members.userName, members.firstName, members.lastName, " .
            "events.bookCode, projects.bookProject, projects.sourceBible, mychks.step as myStep, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, book_info.name AS bookName " .
            "FROM " . PREFIX . "checkers_l2 AS chks " .
            "LEFT JOIN " . PREFIX . "members AS members ON chks.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS events ON events.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "checkers_l2 as mychks ON mychks.memberID = :memberID AND mychks.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = events.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON events.bookCode = book_info.code " .
            "WHERE chks.eventID IN(SELECT eventID FROM " . PREFIX . "checkers_l2 WHERE memberID = :memberID)";

        $prepare = [
            ":memberID" => Session::get("memberID")
        ];

        $notifications = $this->db->select($sql, $prepare);
        $notifs = [];

        foreach ($notifications as $notification) {
            if ($notification->memberID != Session::get("memberID")) {
                $notification->manageMode = "l2";

                $peerCheck = (array)json_decode($notification->peerCheck, true);
                $kwCheck = (array)json_decode($notification->kwCheck, true);
                $crCheck = (array)json_decode($notification->crCheck, true);

                foreach ($peerCheck as $chapter => $data) {
                    // Exclude taken chapters
                    if ($data["memberID"] > 0) continue;

                    $note = clone $notification;
                    $note->currentChapter = $chapter;
                    $note->step = EventCheckSteps::PEER_REVIEW;
                    $note->checkerID = 0;
                    $notifs[] = $note;
                }

                foreach ($kwCheck as $chapter => $data) {
                    // Exclude taken chapters
                    if ($data["memberID"] > 0) continue;

                    $note = clone $notification;
                    $note->currentChapter = $chapter;
                    $note->step = EventCheckSteps::KEYWORD_CHECK;
                    $note->checkerID = 0;
                    $notifs[] = $note;
                }

                foreach ($crCheck as $chapter => $data) {
                    // Exclude taken chapters
                    if ($data["memberID"] > 0) continue;

                    $note = clone $notification;
                    $note->currentChapter = $chapter;
                    $note->step = EventCheckSteps::CONTENT_REVIEW;
                    $note->checkerID = 0;
                    $notifs[] = $note;
                }
            }
        }

        return $notifs;
    }

    /**
     * Get notifications for Level 3 events
     * @return array
     */
    public function getNotificationsL3()
    {
        $sql = "SELECT chks.*, " .
            "members.userName, members.firstName, members.lastName, " .
            "events.bookCode, projects.bookProject, projects.sourceBible, mychks.step as myStep, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, book_info.name AS bookName " .
            "FROM " . PREFIX . "checkers_l3 AS chks " .
            "LEFT JOIN " . PREFIX . "members AS members ON chks.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS events ON events.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "checkers_l3 as mychks ON mychks.memberID = :memberID AND mychks.eventID = chks.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = events.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON events.bookCode = book_info.code " .
            "WHERE chks.eventID IN (SELECT eventID FROM " . PREFIX . "checkers_l3 WHERE memberID = :memberID)";

        $prepare = [
            ":memberID" => Session::get("memberID")
        ];

        $notifications = $this->db->select($sql, $prepare);
        $notifs = [];

        foreach ($notifications as $notification) {
            if ($notification->step != EventCheckSteps::PEER_REVIEW_L3)
                continue;

            if (Session::get("memberID") == $notification->memberID)
                continue;

            // Peer check notifications
            $peerCheck = (array)json_decode($notification->peerCheck, true);
            foreach ($peerCheck as $chapter => $data) {
                // Exclude taken chapters
                if ($data["memberID"] > 0)
                    continue;

                $notif = clone $notification;
                $notif->step = EventCheckSteps::PEER_REVIEW_L3;
                $notif->currentChapter = $chapter;
                $notif->manageMode = "l3";
                $notifs[] = $notif;
            }
        }

        return $notifs;
    }

    /**
     * Get notifications for Level 2 events
     * @return array
     */
    public function getNotificationsSun()
    {
        $sql = "SELECT trs.*, " .
            "members.userName, members.firstName, members.lastName, " .
            "events.bookCode, projects.sourceBible, projects.bookProject, mytrs.step as myStep, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, book_info.name AS bookName " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS events ON events.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "translators as mytrs ON mytrs.memberID = :memberID AND mytrs.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = events.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON events.bookCode = book_info.code " .
            "WHERE trs.eventID IN(SELECT eventID FROM " . PREFIX . "translators WHERE memberID = :memberID) " .
            "AND projects.bookProject = 'sun' ";

        $prepare = [
            ":memberID" => Session::get("memberID")
        ];

        $notifications = $this->db->select($sql, $prepare);
        $notifs = [];

        foreach ($notifications as $notification) {
            // Theological check notifications
            if ($notification->memberID != Session::get("memberID")) {
                $kwCheck = (array)json_decode($notification->kwCheck, true);
                foreach ($kwCheck as $chapter => $data) {
                    // Exclude taken chapters
                    if ($data["memberID"] > 0) continue;

                    $notif = clone $notification;
                    $notif->step = EventSteps::THEO_CHECK;
                    $notif->currentChapter = $chapter;
                    $notif->manageMode = $notification->bookProject;
                    $notifs[] = $notif;
                }
            }

            // Verse-by-verse check notifications
            if ($notification->memberID != Session::get("memberID")) {
                $crCheck = (array)json_decode($notification->crCheck, true);
                foreach ($crCheck as $chapter => $data) {
                    // Exclude taken chapters
                    if ($data["memberID"] > 0) continue;

                    $notif = clone $notification;
                    $notif->step = EventSteps::CONTENT_REVIEW;
                    $notif->currentChapter = $chapter;
                    $notif->manageMode = $notification->bookProject;
                    $notifs[] = $notif;
                }
            }
        }

        return $notifs;
    }

    /**
     * Get notifications for Level 2 events
     * @return array
     */
    public function getNotificationsRadio()
    {
        $sql = "SELECT trs.*, " .
            "members.userName, members.firstName, members.lastName, " .
            "events.bookCode, projects.sourceBible, projects.bookProject, mytrs.step as myStep, " .
            "t_lang.langName AS tLang, s_lang.langName AS sLang, book_info.name AS bookName " .
            "FROM " . PREFIX . "translators AS trs " .
            "LEFT JOIN " . PREFIX . "members AS members ON trs.memberID = members.memberID " .
            "LEFT JOIN " . PREFIX . "events AS events ON events.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "translators as mytrs ON mytrs.memberID = :memberID AND mytrs.eventID = trs.eventID " .
            "LEFT JOIN " . PREFIX . "projects AS projects ON projects.projectID = events.projectID " .
            "LEFT JOIN " . PREFIX . "languages AS t_lang ON projects.targetLang = t_lang.langID " .
            "LEFT JOIN " . PREFIX . "languages AS s_lang ON projects.sourceLangID = s_lang.langID " .
            "LEFT JOIN " . PREFIX . "book_info AS book_info ON events.bookCode = book_info.code " .
            "WHERE trs.eventID IN (SELECT eventID FROM " . PREFIX . "translators WHERE memberID = :memberID AND isChecker=1) " .
            "AND projects.bookProject = 'rad' ";

        $prepare = [
            ":memberID" => Session::get("memberID")
        ];

        $notifications = $this->db->select($sql, $prepare);
        $notifs = [];

        foreach ($notifications as $notification) {
            // Peer check notifications
            if ($notification->memberID != Session::get("memberID")) {
                $peerCheck = (array)json_decode($notification->peerCheck, true);
                foreach ($peerCheck as $chapter => $data) {
                    // Exclude taken chapters
                    if ($data["memberID"] > 0) continue;

                    $notif = clone $notification;
                    $notif->step = EventSteps::PEER_REVIEW;
                    $notif->currentChapter = $chapter;
                    $notif->manageMode = $notification->bookProject;
                    $notifs[] = $notif;
                }
            }
        }

        return $notifs;
    }

    /** Get list of all languages
     * @param null $isGW (true - gateway, false - other, null - all)
     * @param null $langs filter by list of lang ids
     * @return array
     */
    public function getAllLanguages($isGW = null, $langs = null)
    {
        $builder = $this->db->table("languages");

        if ($isGW !== null) {
            $builder->where("languages.isGW", $isGW);
        }
        if (is_array($langs) && !empty($langs)) {
            $builder->whereIn("languages.langID", $langs);
        }

        return $builder->select("languages.langID", "languages.langName", "languages.angName", "gateway_languages.glID")
            ->leftJoin("gateway_languages", "languages.langID", "=", "gateway_languages.gwLang")
            ->orderBy("languages.langID")->get();
    }

    public function getAdminLanguages($memberID = null)
    {
        $builder = $this->db->table("events")
            ->select("events.eventID", "events.admins", "events.admins_l2", "events.admins_l3",
                "projects.gwLang", "projects.targetLang")
            ->leftJoin("projects", "events.projectID", "=", "projects.projectID");

        if ($memberID != null) {
            $builder->where("events.admins", "LIKE", "%\"$memberID\"%")
                ->orWhere("events.admins_l2", "LIKE", "%\"$memberID\"%")
                ->orWhere("events.admins_l3", "LIKE", "%\"$memberID\"%");
        }

        return $builder->get();
    }

    public function getSuperadminLanguages($memberID = null)
    {
        $builder = $this->db->table("gateway_languages")
            ->select("glID", "gwLang", "admins");

        if ($memberID != null) {
            $builder->where("admins", "LIKE", "%\"$memberID\"%");
        }

        return $builder->get();
    }

    public function getBooksOfTranslators()
    {
        return $this->db->table("chapters")
            ->select(["members.userName", "members.firstName", "members.lastName",
                "chapters.chapter", "chapters.done", "book_info.name", "book_info.code", "word_groups.words",
                "projects.bookProject", "projects.targetLang", "languages.angName", "languages.langName"])
            ->leftJoin("members", "chapters.memberID", "=", "members.memberID")
            ->leftJoin("events", "chapters.eventID", "=", "events.eventID")
            ->leftJoin("projects", "events.projectID", "=", "projects.projectID")
            ->leftJoin("book_info", "events.bookCode", "=", "book_info.code")
            ->leftJoin("languages", "projects.targetLang", "=", "languages.langID")
            ->leftJoin("word_groups", function ($join) {
                $join->on("chapters.chapter", "=", "word_groups.groupID")
                    ->where("projects.bookProject", "=", "tw");
            })
            ->orderBy("members.userName")
            ->orderBy("book_info.sort")
            ->orderBy("chapters.chapter")
            ->get();
    }

    public function getEventMemberInfo($eventID, $memberID)
    {
        $sql = "SELECT trs.memberID AS translator, ".
            "proj.bookProject, trs.isChecker, ".
            "l2.memberID AS l2checker, l3.memberID AS l3checker ".
            "FROM ".PREFIX."events AS evnt ".
            "LEFT JOIN ".PREFIX."translators AS trs ON evnt.eventID = trs.eventID ".
            "LEFT JOIN ".PREFIX."checkers_l2 AS l2 ON evnt.eventID = l2.eventID AND l2.memberID = :memberID ".
            "LEFT JOIN ".PREFIX."checkers_l3 AS l3 ON evnt.eventID = l3.eventID AND l3.memberID = :memberID ".
            "LEFT JOIN ".PREFIX."projects AS proj ON evnt.projectID = proj.projectID ".
            "WHERE evnt.eventID = :eventID";

        $prepare = array(
            ":memberID" => $memberID,
            ":eventID" => $eventID);

        return $this->db->select($sql, $prepare);
    }


    /**
     * Create project
     * @param array $data
     * @return string
     */
    public function createProject($data)
    {
        return $this->db->table("projects")
            ->insertGetId($data);
    }

    /**
     * Update project
     * @param array $data
     * @param array $where
     * @return string
     */
    public function updateProject($data, $where)
    {
        return $this->db->table("projects")
            ->where($where)
            ->update($data);
    }

    /**
     * Create event
     * @param array $data
     * @return string
     */
    public function createEvent($data)
    {
        return $this->db->table("events")
            ->insertGetId($data);
    }

    /**
     * Add member as new translator for event
     * @param array $data
     * @return string
     */
    public function addTranslator($data)
    {
        return $this->db->table("translators")
            ->insertGetId($data);
    }

    /**
     * Add member as new Revision checker for event
     * @param array $data
     * @return string
     */
    public function addL2Checker($data)
    {
        return $this->db->table("checkers_l2")
            ->insertGetId($data);
    }

    /**
     * Add member as new Level 3 checker for event
     * @param array $data
     * @return string
     */
    public function addL3Checker($data)
    {
        return $this->db->table("checkers_l3")
            ->insertGetId($data);
    }

    /**
     * Update event
     * @param array $data
     * @param array $where
     * @return int
     */
    public function updateEvent($data, $where)
    {
        return $this->db->table("events")
            ->where($where)
            ->update($data);
    }

    /**
     * Delete event
     * @param array $where
     * @return int
     */
    public function deleteEvent($where)
    {
        return $this->db->table("events")
            ->where($where)
            ->delete();
    }

    /**
     * Update translator
     * @param array $data
     * @param array $where
     * @return int
     */
    public function updateTranslator($data, $where)
    {
        return $this->db->table("translators")
            ->where($where)
            ->update($data);
    }

    /**
     * Update Revision Checker
     * @param array $data
     * @param array $where
     * @return int
     */
    public function updateL2Checker($data, $where)
    {
        return $this->db->table("checkers_l2")
            ->where($where)
            ->update($data);
    }

    /**
     * Update L3 Checker
     * @param array $data
     * @param array $where
     * @return int
     */
    public function updateL3Checker($data, $where)
    {
        return $this->db->table("checkers_l3")
            ->where($where)
            ->update($data);
    }

    /**
     * Assign chapter to translator's queue
     * @param $data
     * @return int
     */
    public function assignChapter($data)
    {
        return $this->db->table("chapters")
            ->insertGetId($data);
    }

    /**
     * Remove chapter from translator's queue
     * @param $where
     * @return int
     */
    public function removeChapter($where)
    {
        return $this->db->table("chapters")
            ->where($where)
            ->delete();
    }

    /**
     * Get next chapter to translate/check
     * @param $eventID
     * @param $memberID
     * @param string $level
     * @return array
     */
    public function getNextChapter($eventID, $memberID, $level = "l1")
    {
        $builder = $this->db->table("chapters")
            ->where(["eventID" => $eventID]);

        if ($level == "l1") {
            $builder->where(["memberID" => $memberID])
                ->where("done", "!=", true);
        } else if ($level == "l2") {
            $builder->where(["l2memberID" => $memberID])
                ->where("l2checked", "!=", true);
        } else if ($level == "l3") {
            $builder->where(["l3memberID" => $memberID])
                ->where("l3checked", "!=", true);
        }

        return $builder->orderBy("chapter")->get();
    }

    /**
     * Update chapter
     * @param array $data
     * @param array $where
     * @return int
     */
    public function updateChapter($data, $where)
    {
        return $this->db->table("chapters")
            ->where($where)
            ->update($data);
    }

    /**
     * Delete tW group
     * @param array $where
     * @return int
     */
    public function deleteWordGroup($where)
    {
        return $this->db->table("word_groups")
            ->where($where)
            ->delete();
    }

    /**
     * Create tW group
     * @param $data
     * @return int
     */
    public function createWordGroup($data)
    {
        return $this->db->table("word_groups")
            ->insertGetId($data);
    }

    /**
     * Get Event Data by eventID OR by projectID and bookCode
     * @param $eventID
     * @param $projectID
     * @param $bookCode
     * @param bool $countMembers
     * @return array
     */
    public function getEvent($eventID, $projectID = null, $bookCode = null, $countMembers = false)
    {
        $table = "translators";
        $builder = $this->db->table("events");
        $select = ["events.*", "book_info.*", "projects.bookProject", "projects.targetLang"];
        if ($countMembers) {
            $select[] = $this->db->raw("COUNT(DISTINCT " . PREFIX . $table . ".memberID) AS translators");
            $select[] = $this->db->raw("COUNT(DISTINCT " . PREFIX . "checkers_l2.memberID) AS checkers_l2");
            $select[] = $this->db->raw("COUNT(DISTINCT " . PREFIX . "checkers_l3.memberID) AS checkers_l3");

            $builder
                ->leftJoin($table, "events.eventID", "=", $table . ".eventID")
                ->leftJoin("checkers_l2", "events.eventID", "=", "checkers_l2.eventID")
                ->leftJoin("checkers_l3", "events.eventID", "=", "checkers_l3.eventID");
        }

        $builder->leftJoin("book_info", "events.bookCode", "=", "book_info.code")
            ->leftJoin("projects", "events.projectID", "=", "projects.projectID")
            ->leftJoin("gateway_languages", "projects.glID", "=", "gateway_languages.glID");

        if ($eventID)
            $builder->where("events.eventID", $eventID);
        else
            $builder->where("events.projectID", $projectID)
                ->where("events.bookCode", $bookCode);

        return $builder->select($select)->get();
    }
}