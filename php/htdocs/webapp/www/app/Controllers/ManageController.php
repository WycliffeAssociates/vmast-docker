<?php
/**
 * Created by mXaln
 */

namespace App\Controllers;

use App\Data\NotificationMapper;
use App\Domain\RenderNotifications;
use App\Models\NewsModel;
use App\Repositories\Event\IEventRepository;
use App\Repositories\Member\IMemberRepository;
use App\Repositories\Resources\IResourcesRepository;
use Helpers\Arrays;
use Helpers\Constants\AssignChapterConfirm;
use Helpers\Constants\InputMode;
use Helpers\Constants\MoveBackConfirm;
use Helpers\Constants\ResourcesCheckSteps;
use Helpers\Constants\RevisionMode;
use Helpers\Constants\TnCheckSteps;
use Helpers\Tools;
use Support\Collection;
use Support\Facades\View;
use Config\Config;
use Helpers\Url;
use Helpers\Gump;
use Helpers\Session;
use App\Core\Controller;
use App\Models\EventsModel;
use App\Models\TranslationsModel;
use Helpers\Constants\EventSteps;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventStates;
use Helpers\Constants\EventMembers;
use AMQPMailer;

class ManageController extends Controller {
    private $eventModel;
    private $translationModel;
    private $newsModel;
    private $notifications;
    private $renderedNotifications;
    private $news;
    private $newNewsCount;

    private $memberRepo;
    private $eventRepo;
    private $resourcesRepo;
    private $member;

    public function __construct(
        IMemberRepository $memberRepo,
        IEventRepository $eventRepo,
        IResourcesRepository $resourcesRepo
    ) {
        parent::__construct();

        $this->memberRepo = $memberRepo;
        $this->eventRepo = $eventRepo;
        $this->resourcesRepo = $resourcesRepo;

        if (Config::get("app.isMaintenance")
            && !in_array($_SERVER['REMOTE_ADDR'], Config::get("app.ips"))) {
            Url::redirect("maintenance");
        }

        if (!Session::get('memberID')) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $response["errorType"] = "logout";
                $response["error"] = __("not_loggedin_error");
                echo json_encode($response);
                exit;
            } else {
                Url::redirect('members/login');
            }
        }

        if (!preg_match("/^\\/events\\/demo|\\/events\\/faq/", $_SERVER["REQUEST_URI"])) {

            $this->member = $this->memberRepo->get(Session::get('memberID'));

            if (!$this->member) Url::redirect("members/login");

            if (!$this->member->verified) {
                Url::redirect("members/error/verification");
            } elseif (!$this->member->profile->complete) {
                Url::redirect("members/profile");
            }

            $this->eventModel = new EventsModel($this->eventRepo);
            $this->translationModel = new TranslationsModel();
            $this->newsModel = new NewsModel();

            $this->notifications = $this->eventModel->getNotifications();
            $this->notifications = Arrays::append($this->notifications, $this->eventModel->getNotificationsOther());
            $this->notifications = Arrays::append($this->notifications, $this->eventModel->getNotificationsRevision());
            $this->notifications = Arrays::append($this->notifications, $this->eventModel->getNotificationsL3());
            $this->notifications = Arrays::append($this->notifications, $this->eventModel->getNotificationsSun());
            $this->notifications = Arrays::append($this->notifications, $this->eventModel->getNotificationsRadio());

            $notifications = $this->eventRepo->getToNotifications(Session::get("memberID"))
                ->map(function($item) {
                    return NotificationMapper::toData($item);
                })->toArray();
            $this->notifications = Arrays::append($this->notifications, $notifications);

            $renderNotifications = new RenderNotifications($this->notifications);
            $this->renderedNotifications = $renderNotifications->render();

            $this->news = $this->newsModel->getNews();
            $this->newNewsCount = 0;
            foreach ($this->news as $news) {
                if (!isset($_COOKIE["newsid" . $news->id]))
                    $this->newNewsCount++;
            }
        }
    }

    public function manage($eventID)
    {
        $event = $this->eventRepo->get($eventID);

        if ($event) {
            if (!$this->isAdmin($event)) {
                Url::redirect("events");
            }

            if ($event->project->bookProject == "tw") {
                Url::redirect("events/manage-tw/" . $event->eventID);
            } elseif ($event->project->bookProject == "bca") {
                Url::redirect("events/manage-words/" . $event->eventID);
            }

            $tmpChapters = [];
            if (in_array($event->project->bookProject, ["tn","bc"]))
                $tmpChapters[0] = [];

            for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
                $tmpChapters[$i] = [];
            }

            $chapters = $this->eventModel->getChapters($event->eventID);
            foreach ($chapters as $chapter) {
                $tmp["trID"] = $chapter["trID"];
                $tmp["memberID"] = $chapter["memberID"];
                $tmp["chunks"] = $chapter["chunks"] ? json_decode($chapter["chunks"], true) : [];
                $tmp["done"] = $chapter["done"];
                $tmp["verbCheck"] = $chapter["verbCheck"] ? json_decode($chapter["verbCheck"], true) : [];
                $tmp["kwCheck"] = $chapter["kwCheck"] ? json_decode($chapter["kwCheck"], true) : [];
                $tmp["crCheck"] = $chapter["crCheck"] ? json_decode($chapter["crCheck"], true) : [];
                $tmp["peerCheck"] = $chapter["peerCheck"] ? json_decode($chapter["peerCheck"], true) : [];
                $tmp["otherCheck"] = $chapter["otherCheck"] ? json_decode($chapter["otherCheck"], true) : [];
                $tmp["step"] = $chapter["step"];
                $tmp["currentChapter"] = $chapter["currentChapter"];

                $tmpChapters[$chapter["chapter"]] = $tmp;
            }

            if ($event->project->sourceBible == "odb") {
                $data["odb"] = $this->resourcesRepo->getOtherResource(
                    $event->project->sourceLangID,
                    "odb",
                    $event->bookCode
                );
            } elseif ($event->project->bookProject == "rad") {
                $data["rad"] = $this->resourcesRepo->getOtherResource(
                    $event->project->sourceLangID,
                    "rad",
                    $event->bookCode
                );
            }

            $members = $event->translators;

            if (!empty($_POST)) {
                if (!empty(array_filter($tmpChapters))) {
                    $updated = $this->eventModel->updateEvent(
                        array(
                            "state" => EventStates::TRANSLATING,
                            "dateFrom" => date("Y-m-d H:i:s", time())),
                        array("eventID" => $eventID));
                    if ($updated)
                        Url::redirect("events/manage/" . $eventID);
                } else {
                    $error[] = __("event_chapters_error");
                }
            }
        } else {
            $error[] = __("empty_or_not_permitted_event_error");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        return View::make('Events/Manage')
            ->shares("title", __("manage_event"))
            ->shares("data", $data)
            ->shares("event", $event)
            ->shares("chapters", $tmpChapters)
            ->shares("members", $members)
            ->shares("error", @$error);
    }

    public function manageTw($eventID)
    {
        $event = $this->eventRepo->get($eventID);

        if ($event) {
            if (!$this->isAdmin($event)) {
                Url::redirect("events");
            }

            $data["word_groups"] = $this->eventModel->getWordGroups(["eventID" => $event->eventID]);
            $data["words_in_groups"] = [];

            foreach ($data["word_groups"] as $group) {
                $words = $group->words ? json_decode($group->words, true) : [];
                $data["words_in_groups"] = Arrays::append($data["words_in_groups"], $words);
            }

            $tmpChapters = [];

            foreach ($data["word_groups"] as $group) {
                $tmpChapters[$group->groupID] = [];
            }

            $data["words"] = $this->resourcesRepo->getTw(
                $event->project->resLangID,
                $event->bookInfo->name
            );

            $chapters = $this->eventModel->getChapters($event->eventID, null, null, $event->project->bookProject);
            foreach ($chapters as $chapter) {
                $tmp["trID"] = $chapter["trID"];
                $tmp["memberID"] = $chapter["memberID"];
                $tmp["chunks"] = $chapter["chunks"] ? json_decode($chapter["chunks"], true) : [];
                $tmp["done"] = $chapter["done"];
                $tmp["otherCheck"] = $chapter["otherCheck"] ? json_decode($chapter["otherCheck"], true) : [];
                $tmp["peerCheck"] = $chapter["peerCheck"] ? json_decode($chapter["peerCheck"], true) : [];

                $tmpChapters[$chapter["chapter"]] = $tmp;
            }

            $members = $event->translators;

            if (isset($_POST) && !empty($_POST)) {
                if (!empty(array_filter($tmpChapters))) {
                    $updated = $this->eventModel->updateEvent(
                        array(
                            "state" => EventStates::TRANSLATING,
                            "dateFrom" => date("Y-m-d H:i:s", time())),
                        array("eventID" => $eventID));
                    if ($updated)
                        Url::redirect("events/manage-tw/" . $eventID);
                } else {
                    $error[] = __("event_chapters_error");
                }
            }
        } else {
            $error[] = __("empty_or_not_permitted_event_error");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        return View::make('Events/ManageTw')
            ->shares("title", __("manage_event"))
            ->shares("event", $event)
            ->shares("members", $members)
            ->shares("chapters", $tmpChapters)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    public function manageWords($eventID)
    {
        $event = $this->eventRepo->get($eventID);

        if ($event) {
            if (!$this->isAdmin($event)) {
                Url::redirect("events");
            }

            if ($event->project->bookProject != "bca") {
                Url::redirect("events/manage/" . $event->eventID);
            }

            $members = $event->translators;
            $tmpChapters = [];
            foreach ($event->words as $word) {
                $tmpChapters[$word->wordID] = [];
            }

            $chapters = $this->eventModel->getChapters(
                $event->eventID,
                null,
                null,
                $event->project->bookProject
            );

            foreach ($chapters as $chapter) {
                $tmp["trID"] = $chapter["trID"];
                $tmp["memberID"] = $chapter["memberID"];
                $tmp["chunks"] = $chapter["chunks"] ? json_decode($chapter["chunks"], true) : [];
                $tmp["done"] = $chapter["done"];

                $member = $members->find($chapter["memberID"]);
                $name = $member
                    ? $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."
                    : $chapter["memberID"];
                $tmp["memberName"] = $name;

                $otherCheck = $chapter["otherCheck"] ? json_decode($chapter["otherCheck"], true) : [];
                $peerCheck = $chapter["peerCheck"] ? json_decode($chapter["peerCheck"], true) : [];

                $hasOther = !empty($otherCheck)
                    && array_key_exists($chapter["chapter"], $otherCheck)
                    && $otherCheck[$chapter["chapter"]]["memberID"] > 0;
                $hasPeer = !empty($peerCheck)
                    && array_key_exists($chapter["chapter"], $peerCheck)
                    && $peerCheck[$chapter["chapter"]]["memberID"] > 0;

                $tmp["otherName"] = "unknown";
                $tmp["peerName"] = "unknown";

                if ($hasOther) {
                    $member = $members->find($otherCheck[$chapter["chapter"]]["memberID"]);
                    $otherName = $member
                        ? $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."
                        : $otherCheck[$chapter["chapter"]]["memberID"];
                    $tmp["otherName"] = $otherName;
                    $tmp["otherCheck"] = $otherCheck;
                }

                if ($hasPeer) {
                    $member = $members->find($peerCheck[$chapter["chapter"]]["memberID"]);
                    $peerName = $member
                        ? $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."
                        : $peerCheck[$chapter["chapter"]]["memberID"];
                    $tmp["peerName"] = $peerName;
                    $tmp["peerCheck"] = $peerCheck;
                }

                $tmp["hasOtherCheck"] = $hasOther;
                $tmp["hasPeerCheck"] = $hasPeer;

                $tmpChapters[$chapter["chapter"]] = $tmp;
            }

            if (isset($_POST) && !empty($_POST)) {
                if (!empty(array_filter($tmpChapters))) {
                    $updated = $this->eventModel->updateEvent(
                        array(
                            "state" => EventStates::TRANSLATING,
                            "dateFrom" => date("Y-m-d H:i:s", time())),
                        array("eventID" => $eventID));
                    if ($updated)
                        Url::redirect("events/manage-words/" . $eventID);
                } else {
                    $error[] = __("event_chapters_error");
                }
            }
        } else {
            $error[] = __("empty_or_not_permitted_event_error");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        return View::make('Events/ManageWords')
            ->shares("title", __("manage_event"))
            ->shares("data", $data)
            ->shares("event", $event)
            ->shares("chapters", $tmpChapters)
            ->shares("members", $members)
            ->shares("error", @$error);
    }

    public function manageRevision($eventID)
    {
        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if ($event) {
            if (!$this->isAdmin($event)) {
                Url::redirect("events");
            }

            if ($event->state != EventStates::L2_RECRUIT &&
                $event->state != EventStates::L2_CHECK &&
                $event->state != EventStates::L2_CHECKED) {
                Url::redirect("events");
            }

            $tmpChapters = [];
            for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
                $tmpChapters[$i] = [];
            }

            $chapters = $this->eventModel->getChapters($event->eventID, null, null, "l2");

            foreach ($chapters as $chapter) {
                if ($chapter["l2memberID"] == 0) continue;

                $tmp["l2chID"] = $chapter["l2chID"];
                $tmp["l2memberID"] = $chapter["l2memberID"];
                $tmp["chunks"] = $chapter["chunks"] ? json_decode($chapter["chunks"], true) : [];
                $tmp["l2checked"] = $chapter["l2checked"];
                $tmp["peerCheck"] = $chapter["peerCheck"] ? json_decode($chapter["peerCheck"], true) : [];
                $tmp["kwCheck"] = $chapter["kwCheck"] ? json_decode($chapter["kwCheck"], true) : [];
                $tmp["crCheck"] = $chapter["crCheck"] ? json_decode($chapter["crCheck"], true) : [];

                $tmpChapters[$chapter["chapter"]] = $tmp;
            }

            $members = $event->checkersL2;

            if (isset($_POST) && !empty($_POST)) {
                if (!empty(array_filter($tmpChapters))) {
                    $updated = $this->eventModel->updateEvent(
                        ["state" => EventStates::L2_CHECK],
                        ["eventID" => $eventID]);
                    if ($updated)
                        Url::redirect("events/manage-revision/" . $eventID);
                } else {
                    $error[] = __("event_chapters_error");
                }
            }
        } else {
            $error[] = __("empty_or_not_permitted_event_error");
        }

        return View::make('Events/ManageRevision')
            ->shares("title", __("manage_event"))
            ->shares("data", $data)
            ->shares("event", $event)
            ->shares("chapters", $tmpChapters)
            ->shares("members", $members)
            ->shares("error", @$error);
    }

    public function manageReview($eventID)
    {
        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if ($event) {
            if (!$this->isAdmin($event)) {
                Url::redirect("events");
            }

            if ($event->state != EventStates::L3_RECRUIT &&
                $event->state != EventStates::L3_CHECK &&
                $event->state != EventStates::COMPLETE) {
                Url::redirect("events");
            }

            $tmpChapters = [];
            if ($event->project->bookProject == "tn")
                $tmpChapters[0] = [];

            for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
                $tmpChapters[$i] = [];
            }

            $chapters = $this->eventModel->getChapters($event->eventID, null, null, "l3");

            foreach ($chapters as $chapter) {
                if ($chapter["l3memberID"] == 0) continue;

                $tmp["l3chID"] = $chapter["l3chID"];
                $tmp["l3memberID"] = $chapter["l3memberID"];
                $tmp["chunks"] = $chapter["chunks"] ? json_decode($chapter["chunks"], true) : [];
                $tmp["l3checked"] = $chapter["l3checked"];
                $tmp["peerCheck"] = $chapter["peerCheck"] ? json_decode($chapter["peerCheck"], true) : [];

                $tmpChapters[$chapter["chapter"]] = $tmp;
            }

            $members = $event->checkersL3;

            if (isset($_POST) && !empty($_POST)) {
                if (!empty(array_filter($tmpChapters))) {
                    $updated = $this->eventModel->updateEvent(
                        array(
                            "state" => EventStates::L3_CHECK,
                            /*"dateFrom" => date("Y-m-d H:i:s", time())*/),
                        array("eventID" => $eventID));
                    if ($updated)
                        Url::redirect("events/manage-review/" . $eventID);
                } else {
                    $error[] = __("event_chapters_error");
                }
            }
        } else {
            $error[] = __("empty_or_not_permitted_event_error");
        }

        return View::make('Events/ManageL3')
            ->shares("title", __("manage_event"))
            ->shares("data", $data)
            ->shares("event", $event)
            ->shares("chapters", $tmpChapters)
            ->shares("members", $members)
            ->shares("error", @$error);
    }

    public function moveStepBack() {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $memberID = isset($_POST["memberID"]) && $_POST["memberID"] != "" ? (integer)$_POST["memberID"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : 0;
        $confirm = isset($_POST["confirm"]) && $_POST["confirm"] != "" ? (integer)$_POST["confirm"] : 0;
        $manageMode = isset($_POST["manageMode"]) && $_POST["manageMode"] != "" ? $_POST["manageMode"] : "l1";

        if ($eventID !== null && $memberID !== null) {
            $event = $this->eventRepo->get($eventID);
            $actionMember = $this->memberRepo->get($memberID);
            $memberName = $actionMember->firstName . " " . mb_substr($actionMember->lastName, 0, 1) . ".";
            $chk = false;

            if ($event) {
                if (!$this->isAdmin($event)) {
                    $response["error"] = __("not_enough_rights_error");
                    echo json_encode($response);
                    return;
                }

                $chapterObject = $event->chapters->filter(function($item) use ($chapter) {
                    return $item->chapter == $chapter;
                })->first();
                $chapterDone = $chapterObject ? $chapterObject->done : false;

                $mode = $event->project->bookProject;
                if ($manageMode == "l1" && $event->inputMode != InputMode::NORMAL) {
                    $mode = $event->inputMode;
                } elseif ($event->project->sourceBible == "odb") {
                    $mode = "odb" . $mode;
                } elseif ($manageMode == "l2") {
                    if ($event->project->bookProject == "sun") {
                        $mode = $manageMode . "_sun";
                    } elseif ($event->revisionMode == RevisionMode::MINOR) {
                        $mode = $manageMode . "_minor";
                    }
                }

                if ($manageMode == "l2") {
                    $member = $actionMember->checkersL2->where("eventID", $eventID, false)->first();
                    $prevState = EventStates::L2_CHECK;
                    $nextState = EventStates::L3_RECRUIT;
                    $finishedState = EventStates::L2_CHECKED;
                    $allSteps = EventCheckSteps::enumArray($mode);
                } elseif ($manageMode == "l3") {
                    $member = $actionMember->checkersL3->where("eventID", $eventID, false)->first();
                    $prevState = EventStates::L3_CHECK;
                    $nextState = EventStates::COMPLETE;
                    $finishedState = EventStates::COMPLETE;
                    $allSteps = EventCheckSteps::enumArray($manageMode);
                } else {
                    $member = $actionMember->translators->where("eventID", $eventID, false)->first();
                    $prevState = EventStates::TRANSLATING;
                    if (Tools::isHelp($mode)) {
                        if ($mode == "tn") {
                            $nextState = EventStates::L3_RECRUIT;
                        } else {
                            $nextState = EventStates::COMPLETE;
                        }
                    } else {
                        $nextState = EventStates::L2_RECRUIT;
                    }
                    $finishedState = EventStates::TRANSLATED;
                    if (Tools::isHelp($mode)) {
                        $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                        $chk = array_key_exists($chapter, $otherCheck);
                    }
                    $allSteps = EventSteps::enumArray($mode, $chk);
                }

                if ($member) {
                    if ($confirm < MoveBackConfirm::EVENT_COMPLETE && EventStates::enum($event->state) >= EventStates::enum($finishedState)) {
                        $response["confirm"] = MoveBackConfirm::EVENT_COMPLETE;
                        $response["message"] = __("event_finished_error");
                        echo json_encode($response);
                        return;
                    }

                    if (EventStates::enum($event->state) >= EventStates::enum($nextState) && $manageMode != "l3") {
                        $response["error"] = __("event_finished_not_possible_error");
                        echo json_encode($response);
                        return;
                    }

                    $event->update(["state" => $prevState]);

                    $currentStep = $this->getMemberCurrentStep($member, $chapter, $chapterDone, $mode, $manageMode);
                    $prevStep = $this->getMemberPreviousStep($currentStep, $allSteps, $mode, $chk, $chapter);
                    $isCheck = Tools::isHelp($mode) && $currentStep != EventSteps::PRAY && $chk;
                    $prevStepName = Tools::getStepName($prevStep, $mode, $isCheck);

                    if ($prevStep == EventSteps::NONE) {
                        $response["error"] = __("move_back_step_not_possible");
                        echo json_encode($response);
                        return;
                    }

                    if ($confirm < MoveBackConfirm::MOVE_STEP) {
                        if (Tools::isHelp($mode) && $currentStep != EventSteps::PRAY) {
                            $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                            if ($chk && $otherCheck[$chapter]["memberID"] > 0) {
                                $checkerMember = $this->memberRepo->get($otherCheck[$chapter]["memberID"]);
                                $memberName = $checkerMember->firstName . " " . mb_substr($checkerMember->lastName, 0, 1) . ".";
                            }
                        } elseif ($mode == "sun") {
                            if ($prevStep == EventSteps::FINAL_REVIEW || $prevStep == EventSteps::CONTENT_REVIEW) {
                                $crCheck = $member->crCheck ? json_decode($member->crCheck, true) : [];
                                if ($crCheck[$chapter]["memberID"] > 0) {
                                    $checkerMember = $this->memberRepo->get($crCheck[$chapter]["memberID"]);
                                    $memberName = $checkerMember->firstName . " " . mb_substr($checkerMember->lastName, 0, 1) . ".";
                                }
                            } elseif ($prevStep == EventSteps::THEO_CHECK) {
                                $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                                if ($kwCheck[$chapter]["memberID"] > 0) {
                                    $checkerMember = $this->memberRepo->get($kwCheck[$chapter]["memberID"]);
                                    $memberName = $checkerMember->firstName . " " . mb_substr($checkerMember->lastName, 0, 1) . ".";
                                }
                            }
                        } elseif ($mode == "l2_sun") {
                            if ($prevStep == EventCheckSteps::PEER_REVIEW) {
                                $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                                if ($peerCheck[$chapter]["memberID"] > 0) {
                                    $checkerMember = $this->memberRepo->get($peerCheck[$chapter]["memberID"]);
                                    $memberName = $checkerMember->firstName . " " . mb_substr($checkerMember->lastName, 0, 1) . ".";
                                }
                            }
                        }

                        $response["confirm"] = MoveBackConfirm::MOVE_STEP;
                        $response["message"] = __("move_member_step_back", [
                            "member" => $memberName,
                            "step" => __($prevStepName)
                        ]);
                        echo json_encode($response);
                        return;
                    }

                    $postData = $this->moveMemberStepBack($member, $chapter, $mode, $currentStep, $prevStep, $confirm, $chk);

                    if (!empty($postData)) {
                        if (in_array("hasTranslation", $postData, true)) {
                            $response["confirm"] = MoveBackConfirm::REMOVE_TRANSLATION;
                            $response["message"] = __("chapter_has_translation");
                            echo json_encode($response);
                            return;
                        } elseif (in_array("otherChapterInProgress", $postData, true)) {
                            $response["error"] = __("member_work_in_progress");
                            echo json_encode($response);
                            return;
                        }

                        if (array_key_exists("translations", $postData) && $manageMode == "l1") {
                            unset($postData["translations"]);
                            $event->translations()->where("chapter", $chapter)->delete();
                            $event->comments()->where("chapter", $chapter)->where("level", 1)->delete();
                        }

                        $member->update($postData);

                        $response["success"] = true;
                        $response["message"] = !$chk && $manageMode == "l1"
                            ? __("moved_back_success")
                            : __("checker_moved_back_success");
                    } else {
                        $response["error"] = __("wrong_parameters");
                    }
                } else {
                    $response["error"] = __("wrong_parameters");
                }
            } else {
                $response["error"] = __("wrong_parameters");
            }
        }

        echo json_encode($response);
    }

    private function moveMemberStepBack($member, $chapter, $mode, $fromStep, $toStep, $confirm, $chk) {
        if (EventStates::enum($member->event->state) >= EventStates::enum(EventStates::L3_CHECK)) {
            return $this->moveMemberStepBackReview($member, $chapter, "l3", $fromStep, $toStep);
        } elseif (EventStates::enum($member->event->state) >= EventStates::enum(EventStates::L2_CHECK)) {
            return $this->moveMemberStepBackRevision($member, $chapter, $mode, $fromStep, $toStep);
        }

        return $this->moveMemberStepBackL1($member, $chapter, $mode, $fromStep, $toStep, $confirm, $chk);
    }

    private function moveMemberStepBackL1($member, $chapter, $mode, $fromStep, $toStep, $confirm, $chk) {
        $postData = [];

        // Level 1
        // do not allow to move from "none" and "preparation" steps
        // Exception is HELPS mode, when taking chapter from checker to translator
        if (!Tools::isHelp($mode) && !$chk && $toStep != EventSteps::SELF_CHECK) {
            if (EventSteps::enum($fromStep, $mode, $chk) < 2)
                return $postData;
        }

        // Do not allow to move back more than one step at a time
        // Exception is HELPS mode, when taking chapter from checker to translator
        if (!Tools::isHelp($mode) && !$chk && $toStep != EventSteps::SELF_CHECK) {
            if ((EventSteps::enum($fromStep, $mode, $chk) - EventSteps::enum($toStep, $mode)) > 1)
                return $postData;
        }

        $initialChapter = $mode == "tn" ? -1 : 0;

        switch ($toStep) {
            case EventSteps::PRAY:
                if (Tools::isHelp($mode) && $chk) {
                    $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                    if (array_key_exists($chapter, $otherCheck)) {
                        $otherCheck[$chapter]["done"] = TnCheckSteps::PRAY;
                        $postData["otherCheck"] = json_encode($otherCheck);
                    }
                } else {
                    $postData["step"] = EventSteps::PRAY;
                    $postData["currentChapter"] = $initialChapter;
                }
                break;

            case EventSteps::CONSUME:
                $postData["step"] = EventSteps::CONSUME;

                if ($mode != "tn") {
                    if (in_array($mode, ["obs","odbsun"])) {
                        $postData["currentChunk"] = 0;
                    } else {
                        $verbCheck = $member->verbCheck ? json_decode($member->verbCheck, true) : [];
                        if (array_key_exists($chapter, $verbCheck))
                            unset($verbCheck[$chapter]);
                        $postData["verbCheck"] = json_encode($verbCheck);
                    }
                } else {
                    if ($chk) {
                        $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                        if (array_key_exists($chapter, $otherCheck)) {
                            $otherCheck[$chapter]["done"] = TnCheckSteps::CONSUME;
                            $postData["otherCheck"] = json_encode($otherCheck);
                        }
                    } else {
                        $trans = $this->translationModel->getEventTranslation($member->trID, $chapter);
                        if (!empty($trans) && $confirm != MoveBackConfirm::REMOVE_TRANSLATION)
                            return ["hasTranslation"];
                        $this->eventModel->updateChapter(["chunks" => "[]"], [
                            "eventID" => $member->eventID,
                            "chapter" => $chapter]);
                        $postData["step"] = EventSteps::CONSUME;
                        $postData["currentChunk"] = 0;
                        $postData["translations"] = true;
                    }
                }
                break;

            case EventSteps::HIGHLIGHT:
                if ($mode == "tn" && $chk) {
                    $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                    if (array_key_exists($chapter, $otherCheck)) {
                        $otherCheck[$chapter]["done"] = TnCheckSteps::HIGHLIGHT;
                        $postData["otherCheck"] = json_encode($otherCheck);
                    }
                }
                break;

            case EventSteps::VERBALIZE:
                $postData["step"] = EventSteps::VERBALIZE;

                $verbCheck = $member->verbCheck ? json_decode($member->verbCheck, true) : [];
                if (array_key_exists($chapter, $verbCheck)) {
                    unset($verbCheck[$chapter]);
                }
                $postData["verbCheck"] = json_encode($verbCheck);
                break;

            case EventSteps::CHUNKING:
                $trans = $this->translationModel->getEventTranslation($member->trID, $chapter);

                if (!empty($trans) && $confirm != MoveBackConfirm::REMOVE_TRANSLATION)
                    return ["hasTranslation"];

                $this->eventModel->updateChapter(["chunks" => "[]"], [
                    "eventID" => $member->eventID,
                    "chapter" => $chapter]);

                $postData["step"] = EventSteps::CHUNKING;
                $postData["currentChunk"] = 0;
                $postData["translations"] = true;
                break;

            case EventSteps::BLIND_DRAFT:
                $postData["step"] = in_array($mode, ["obs","odbsun"]) ? EventSteps::BLIND_DRAFT : EventSteps::READ_CHUNK;
                $postData["currentChunk"] = 0;
                break;

            case EventSteps::REARRANGE:
                $postData["step"] = EventSteps::REARRANGE;
                $postData["currentChunk"] = 0;
                break;

            case EventSteps::SYMBOL_DRAFT:
                $postData["step"] = EventSteps::SYMBOL_DRAFT;
                $postData["currentChunk"] = 0;
                break;

            case EventSteps::SELF_CHECK:
                $hasWorkChapter = $member->step != EventSteps::NONE && $member->step != EventSteps::PRAY
                    && $member->currentChapter > $initialChapter && $member->currentChapter != $chapter;

                if ($hasWorkChapter && (!Tools::isHelp($mode) || $fromStep == EventSteps::PRAY)) {
                    return ["otherChapterInProgress"];
                } else {
                    if (Tools::isHelp($mode) && $fromStep != EventSteps::PRAY) {
                        $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                        if (array_key_exists($chapter, $otherCheck)) {
                            $otherCheck[$chapter]["done"] = TnCheckSteps::SELF_CHECK;
                            $postData["otherCheck"] = json_encode($otherCheck);
                        }
                    } else {
                        $postData["step"] = EventSteps::SELF_CHECK;
                        $postData["currentChapter"] = $chapter;

                        $this->eventModel->updateChapter(["done" => false], [
                            "eventID" => $member->eventID,
                            "chapter" => $chapter]);

                        $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                        $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                        if (array_key_exists($chapter, $peerCheck))
                            unset($peerCheck[$chapter]);
                        if (array_key_exists($chapter, $otherCheck))
                            unset($otherCheck[$chapter]);
                        $postData["peerCheck"] = json_encode($peerCheck);
                        $postData["otherCheck"] = json_encode($otherCheck);

                        if (in_array($mode, ["odbsun","sun"])) {
                            $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                            if (array_key_exists($chapter, $kwCheck))
                                unset($kwCheck[$chapter]);
                            $postData["kwCheck"] = json_encode($kwCheck);
                        }
                    }
                }
                break;

            case EventSteps::MULTI_DRAFT:
                $postData["step"] = EventSteps::MULTI_DRAFT;
                break;

            case EventSteps::PEER_REVIEW:
                $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                if (Tools::isHelp($mode) && $chk) {
                    $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                    if (array_key_exists($chapter, $peerCheck)) {
                        $peerCheck[$chapter] = ["memberID" => 0, "done" => 0];
                    }
                    if (array_key_exists($chapter, $otherCheck)) {
                        $otherCheck[$chapter]["done"] = $mode == "tn" ? TnCheckSteps::PEER_REVIEW : ResourcesCheckSteps::PEER_REVIEW;
                    }
                    $postData["peerCheck"] = json_encode($peerCheck);
                    $postData["otherCheck"] = json_encode($otherCheck);
                } else {
                    $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                    $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                    if (array_key_exists($chapter, $kwCheck))
                        unset($kwCheck[$chapter]);
                    $peerCheck[$chapter] = ["memberID" => 0, "done" => 0];
                    $postData["peerCheck"] = json_encode($peerCheck);
                    $postData["kwCheck"] = json_encode($kwCheck);
                }
                break;

            case EventSteps::KEYWORD_CHECK:
            case EventSteps::THEO_CHECK:
                if (Tools::isHelp($mode) && $chk) {
                    $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                    $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                    if (array_key_exists($chapter, $peerCheck))
                        unset($peerCheck[$chapter]);
                    if (array_key_exists($chapter, $otherCheck)) {
                        if ($mode == "tn") {
                            $otherCheck[$chapter]["done"] = $chapter == 0 ? TnCheckSteps::SELF_CHECK : TnCheckSteps::KEYWORD_CHECK;
                        } else {
                            $otherCheck[$chapter]["done"] = ResourcesCheckSteps::KEYWORD_CHECK;
                        }
                        $postData["peerCheck"] = json_encode($peerCheck);
                        $postData["otherCheck"] = json_encode($otherCheck);
                    }
                } else {
                    $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                    $crCheck = $member->crCheck ? json_decode($member->crCheck, true) : [];
                    if (array_key_exists($chapter, $crCheck))
                        unset($crCheck[$chapter]);
                    $kwCheck[$chapter] = ["memberID" => 0, "done" => 0];
                    $postData["kwCheck"] = json_encode($kwCheck);
                    $postData["crCheck"] = json_encode($crCheck);
                }
                break;

            case EventSteps::CONTENT_REVIEW:
                $crCheck = $member->crCheck ? json_decode($member->crCheck, true) : [];
                $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                $crCheck[$chapter] = ["memberID" => 0, "done" => 0, "memberID2" => 0, "done2" => 0];
                if (array_key_exists($chapter, $otherCheck))
                    unset($otherCheck[$chapter]);
                $postData["crCheck"] = json_encode($crCheck);
                $postData["otherCheck"] = json_encode($otherCheck);
                break;

            case EventSteps::FINAL_REVIEW:
                if ($mode == "sun") {
                    $crCheck = $member->crCheck ? json_decode($member->crCheck, true) : [];
                    if (array_key_exists($chapter, $crCheck)) {
                        $crCheck[$chapter]["done"] = 1;
                    }
                    $postData["crCheck"] = json_encode($crCheck);
                } else {
                    $otherCheck = $member->otherCheck ? json_decode($member->otherCheck, true) : [];
                    $otherCheck[$chapter] = ["memberID" => 0, "done" => 0];
                    $postData["otherCheck"] = json_encode($otherCheck);
                }
                break;
        }

        return $postData;
    }

    private function moveMemberStepBackRevision($member, $chapter, $mode, $fromStep, $toStep) {
        $postData = [];

        // do not allow move from "none" and "preparation" steps
        if (EventCheckSteps::enum($fromStep, $mode) < 2)
            return $postData;

        // Do not allow to move back more than one step at a time
        if ((EventCheckSteps::enum($fromStep, $mode) - EventCheckSteps::enum($toStep, $mode)) > 1)
            return $postData;

        switch ($toStep) {
            case EventCheckSteps::PRAY:
                $postData["step"] = EventCheckSteps::PRAY;
                break;

            case EventCheckSteps::CONSUME:
                $postData["step"] = EventCheckSteps::CONSUME;
                break;

            case EventCheckSteps::SELF_CHECK:
                $hasWorkChapter = $member->step != EventSteps::NONE && $member->step != EventSteps::PRAY
                    && $member->currentChapter > 0 && $member->currentChapter != $chapter;

                if ($hasWorkChapter) {
                    return ["otherChapterInProgress"];
                } else {
                    $this->eventModel->updateChapter(["l2checked" => false], [
                        "eventID" => $member->eventID,
                        "chapter" => $chapter]);

                    $postData["step"] = EventCheckSteps::SELF_CHECK;
                    $postData["currentChapter"] = $chapter;
                    $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                    $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                    if (array_key_exists($chapter, $peerCheck)) {
                        unset($peerCheck[$chapter]);
                    }
                    if (array_key_exists($chapter, $kwCheck)) {
                        unset($kwCheck[$chapter]);
                    }
                    $postData["peerCheck"] = json_encode($peerCheck);
                    $postData["kwCheck"] = json_encode($kwCheck);
                }
                break;

            case EventCheckSteps::PEER_REVIEW:
                $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                if (array_key_exists($chapter, $peerCheck)) {
                    $peerCheck[$chapter] = ["memberID" => 0, "done" => 0];
                }
                if (array_key_exists($chapter, $kwCheck)) {
                    unset($kwCheck[$chapter]);
                }
                $postData["peerCheck"] = json_encode($peerCheck);
                $postData["kwCheck"] = json_encode($kwCheck);
                break;

            case EventCheckSteps::KEYWORD_CHECK:
                $kwCheck = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
                $crCheck = $member->crCheck ? json_decode($member->crCheck, true) : [];
                if (array_key_exists($chapter, $kwCheck)) {
                    $kwCheck[$chapter] = ["memberID" => 0, "done" => 0];
                }
                if (array_key_exists($chapter, $crCheck)) {
                    unset($crCheck[$chapter]);
                }
                $postData["kwCheck"] = json_encode($kwCheck);
                $postData["crCheck"] = json_encode($crCheck);
                break;

            case EventCheckSteps::CONTENT_REVIEW:
                $crCheck = $member->crCheck ? json_decode($member->crCheck, true) : [];
                if (array_key_exists($chapter, $crCheck)) {
                    $crCheck[$chapter] = ["memberID" => 0, "done" => 0];
                }
                $postData["crCheck"] = json_encode($crCheck);
                break;
        }

        return $postData;
    }

    private function moveMemberStepBackReview($member, $chapter, $mode, $fromStep, $toStep) {
        $postData = [];

        // do not allow move from "none" and "preparation" steps
        if (EventCheckSteps::enum($fromStep, $mode) < 2)
            return $postData;

        // Do not allow to move back more than one step at a time
        if ((EventCheckSteps::enum($fromStep, $mode) - EventCheckSteps::enum($toStep, $mode)) > 1)
            return $postData;

        switch ($toStep) {
            case EventCheckSteps::PRAY:
                $postData["step"] = EventSteps::PRAY;

                $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                if (array_key_exists($chapter, $peerCheck))
                    unset($peerCheck[$chapter]);

                $postData["peerCheck"] = json_encode($peerCheck);
                break;

            case EventCheckSteps::PEER_REVIEW_L3:
                $postData["step"] = EventCheckSteps::PEER_REVIEW_L3;

                $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                if (array_key_exists($chapter, $peerCheck))
                    $peerCheck[$chapter]["done"] = 0;

                $postData["peerCheck"] = json_encode($peerCheck);
                break;

            case EventCheckSteps::PEER_EDIT_L3:
                $hasWorkChapter = $member->step != EventSteps::NONE && $member->step != EventSteps::PRAY
                    && $member->currentChapter > 0 && $member->currentChapter != $chapter;

                if ($hasWorkChapter) {
                    return ["otherChapterInProgress"];
                } else {
                    $postData["step"] = EventCheckSteps::PEER_EDIT_L3;
                    $postData["currentChapter"] = $chapter;

                    $peerCheck = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
                    if (array_key_exists($chapter, $peerCheck))
                        $peerCheck[$chapter]["done"] = 1;

                    $postData["peerCheck"] = json_encode($peerCheck);
                }
                break;
        }

        return $postData;
    }

    private function getMemberCurrentStep($member, $chapter, $chapterDone, $mode, $manageMode) {
        if ($manageMode == "l3") {
            $peer = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
            if (array_key_exists($chapter, $peer) && $peer[$chapter]["done"] == 2 && $member->currentChapter != $chapter) {
                $step = EventCheckSteps::FINISHED;
            } else {
                $step = $member->step;
            }
        } elseif ($manageMode == "l2") {
            $peer = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
            $kw = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
            $cr = $member->crCheck ? json_decode($member->crCheck, true) : [];

            $done = 2;
            $peerDone = $mode == "l2_sun" ? 1 : $done;

            if (array_key_exists($chapter, $peer) && $peer[$chapter]["done"] < $peerDone) {
                $step = EventCheckSteps::PEER_REVIEW;
            } elseif (array_key_exists($chapter, $kw) && $kw[$chapter]["done"] < $done) {
                $step = EventCheckSteps::KEYWORD_CHECK;
            } elseif (array_key_exists($chapter, $cr) && $cr[$chapter]["done"] < $done) {
                $step = EventCheckSteps::CONTENT_REVIEW;
            } elseif (array_key_exists($chapter, $cr) && $cr[$chapter]["done"] == $done) {
                $step = EventCheckSteps::FINISHED;
            } elseif ($mode == "l2_sun" && array_key_exists($chapter, $peer) && $peer[$chapter]["done"] == $peerDone) {
                $step = EventCheckSteps::FINISHED;
            } else {
                $step = $member->step;
            }
        } else {
            $peer = $member->peerCheck ? json_decode($member->peerCheck, true) : [];
            $kw = $member->kwCheck ? json_decode($member->kwCheck, true) : [];
            $cr = $member->crCheck ? json_decode($member->crCheck, true) : [];
            $other = $member->otherCheck ? json_decode($member->otherCheck, true) : [];

            $done = 2;
            $finalDone = 1;
            $peerDone = Tools::isHelp($mode) || in_array($mode, ["rad","odbsun"]) ? 1 : $done;
            $kwDone = in_array($mode, ["sun","odbsun"]) ? 1 : $done;
            $crDone = $mode == "odbsun" ? 1 : $done;

            if (array_key_exists($chapter, $peer) && $peer[$chapter]["done"] < $peerDone) {
                $step = EventSteps::PEER_REVIEW;
            } elseif (array_key_exists($chapter, $kw) && $kw[$chapter]["done"] < $kwDone) {
                $step = in_array($mode, ["sun","odbsun"]) ? EventSteps::THEO_CHECK : EventSteps::KEYWORD_CHECK;
            } elseif (array_key_exists($chapter, $cr) && $cr[$chapter]["done"] < $crDone) {
                if ($mode == "sun" && $cr[$chapter]["done"] == 1) {
                    $step = EventSteps::FINAL_REVIEW;
                } else {
                    $step = EventSteps::CONTENT_REVIEW;
                }
            } elseif (array_key_exists($chapter, $other) && $other[$chapter]["done"] < $finalDone) {
                $step = Tools::isHelp($mode) ? EventSteps::PRAY : EventSteps::FINAL_REVIEW;
            } elseif (array_key_exists($chapter, $other) && $other[$chapter]["done"] == $finalDone) {
                $step = EventSteps::FINISHED;
                if (Tools::isHelp($mode)) {
                    if ($mode == "tn") {
                        $step = EventSteps::CONSUME;
                    } else {
                        $step = EventSteps::KEYWORD_CHECK;
                    }
                }
            } elseif ($mode == "speech-to-text" && array_key_exists($chapter, $peer) && $peer[$chapter]["done"] == $peerDone) {
                $step = EventSteps::FINISHED;
            } elseif ($mode == "scripture-input" && $chapterDone) {
                $step = EventSteps::FINISHED;
            } elseif ($mode == "rad" && array_key_exists($chapter, $peer) && $peer[$chapter]["done"] == $peerDone) {
                $step = EventSteps::FINISHED;
            } elseif ($mode == "odbsun" && array_key_exists($chapter, $cr) && $cr[$chapter]["done"] == $peerDone) {
                $step = EventSteps::FINISHED;
            } elseif ($mode == "sun" && array_key_exists($chapter, $cr) && $cr[$chapter]["done"] == $crDone) {
                $step = EventSteps::FINISHED;
            } elseif ($mode == "tn" && array_key_exists($chapter, $other) && $other[$chapter]["done"] == TnCheckSteps::HIGHLIGHT) {
                $step = EventSteps::HIGHLIGHT;
            } elseif ($mode == "tn" && array_key_exists($chapter, $other) && $other[$chapter]["done"] == TnCheckSteps::SELF_CHECK) {
                $step = EventSteps::SELF_CHECK;
            } elseif ($mode == "tn" && array_key_exists($chapter, $other) && $other[$chapter]["done"] == TnCheckSteps::KEYWORD_CHECK) {
                $step = EventSteps::KEYWORD_CHECK;
            } elseif ($mode == "tn" && array_key_exists($chapter, $other) && $other[$chapter]["done"] == TnCheckSteps::PEER_REVIEW) {
                $step = EventSteps::PEER_REVIEW;
            } elseif ($mode == "tn" && array_key_exists($chapter, $other) && $other[$chapter]["done"] == TnCheckSteps::FINISHED) {
                $step = EventSteps::FINISHED;
            } elseif (Tools::isHelp($mode) && $mode != "tn" && array_key_exists($chapter, $other)
                && $other[$chapter]["done"] == ResourcesCheckSteps::PEER_REVIEW) {
                $step = EventSteps::PEER_REVIEW;
            } elseif (Tools::isHelp($mode) && $mode != "tn" && array_key_exists($chapter, $other)
                && $other[$chapter]["done"] == ResourcesCheckSteps::FINISHED) {
                $step = EventSteps::FINISHED;
            } else {
                $step = $member->step;
            }
        }
        return $step;
    }

    private function getMemberPreviousStep($currentStep, $allSteps, $mode, $chk, $chapter) {
        $currentStepNumber = $allSteps[$currentStep];
        $prevStep = array_search(($currentStepNumber - 1), $allSteps);
        $prevStep = $prevStep == EventSteps::READ_CHUNK ? EventSteps::BLIND_DRAFT : $prevStep;

        if (Tools::isHelp($mode) && $chk && ($prevStep == EventSteps::NONE)) {
            $prevStep = EventSteps::SELF_CHECK;
        }
        if ($mode == "tn" && $chapter == 0) {
            if ($prevStep == EventSteps::CONSUME || $prevStep == EventSteps::HIGHLIGHT) {
                $prevStep = EventSteps::PRAY;
            } else {
                $prevStep = EventSteps::SELF_CHECK;
            }
        }
        return $prevStep;
    }

    private function checkHasTranslations($translations, $manageMode) {
        if ($manageMode == "l1") {
            return !$translations->isEmpty();
        } else {
            $checker = $manageMode == "l2" ? EventMembers::L2_CHECKER : EventMembers::L3_CHECKER;
            $checkerTranslations = $translations->filter(function($item) use ($checker) {
                $verses = $item->translatedVerses, 1 ? json_decode($item->translatedVerses, 1) : [];
                return array_key_exists($checker, $verses)
                    && array_key_exists("verses", $verses[$checker])
                    && !empty($verses[$checker]["verses"]);
            });
            return !$checkerTranslations->isEmpty();
        }
    }

    public function setOtherChecker()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $memberID = isset($_POST["memberID"]) && $_POST["memberID"] != "" ? (integer)$_POST["memberID"] : null;

        if ($eventID !== null && $memberID !== null) {
            $event = $this->eventRepo->get($eventID);

            if ($event) {
                if (!$this->isAdmin($event)) {
                    $response["error"] = __("not_enough_rights_error");
                    echo json_encode($response);
                    return;
                }
            }

            $translator = $event->translators->find($memberID);

            if ($translator) {
                $event->translators()->updateExistingPivot($memberID, ["isChecker" => !$translator->pivot->isChecker]);
                $response["success"] = true;
            } else {
                $response["error"] = __("wrong_parameters");
            }
        }

        echo json_encode($response);
    }

    /**
     * Add or remove chapter user translating
     */
    public function assignChapter()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : null;
        $memberID = isset($_POST["memberID"]) && $_POST["memberID"] != "" ? (integer)$_POST["memberID"] : null;
        $confirm = isset($_POST["confirm"]) && $_POST["confirm"] != "" ? (integer)$_POST["confirm"] : 0;
        $action = isset($_POST["action"]) && preg_match("/^(add|delete)$/", $_POST["action"]) ? $_POST["action"] : null;
        $manageMode = isset($_POST["manageMode"]) && $_POST["manageMode"] != "" ? $_POST["manageMode"] : "l1";

        if ($eventID !== null && $chapter !== null && $memberID !== null && $action !== null) {
            $event = $this->eventRepo->get($eventID);

            if ($event) {
                if ($this->isAdmin($event)) {
                    $mode = $event->project->bookProject;

                    $data["chapters"] = [];
                    if (in_array($mode, ["tn","bc"]))
                        $data["chapters"][0] = [];

                    if ($mode == "tw") {
                        $word_groups = $this->eventModel->getWordGroups(["eventID" => $event->eventID]);

                        foreach ($word_groups as $group) {
                            $data["chapters"][$group->groupID] = [];
                        }
                    } elseif ($mode == "bca") {
                        foreach ($event->words as $word) {
                            $data["chapters"][$word->wordID] = [];
                        }
                    } else {
                        for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
                            $data["chapters"][$i] = [];
                        }
                    }

                    $chapters = $this->eventModel->getChapters($event->eventID);

                    foreach ($chapters as $chap) {
                        $tmp["trID"] = $chap["trID"];
                        $tmp["memberID"] = $chap["memberID"];
                        $tmp["chunks"] = $chap["chunks"] ? json_decode($chap["chunks"], true) : [];
                        $tmp["done"] = $chap["done"];
                        $tmp["l2memberID"] = $chap["l2memberID"];
                        $tmp["l2chID"] = $chap["l2chID"];
                        $tmp["l2checked"] = $chap["l2checked"];
                        $tmp["l3memberID"] = $chap["l3memberID"];
                        $tmp["l3chID"] = $chap["l3chID"];
                        $tmp["l3checked"] = $chap["l3checked"];

                        $data["chapters"][$chap["chapter"]] = $tmp;
                    }

                    if (isset($data["chapters"][$chapter]) && empty($data["chapters"][$chapter])) {
                        if ($action == "add") {
                            if ($manageMode == "l2" || $manageMode == "l3") {
                                $response["error"] = __("error_occurred", ["This chapter hasn't been translated."]);
                                echo json_encode($response);
                                exit;
                            }

                            $translator = $event->translators->find($memberID);
                            $postdata = [
                                "trID" => $translator->pivot->trID,
                                "chapter" => $chapter,
                                "chunks" => "[]",
                                "done" => false
                            ];

                            $event->translatorsWithChapters()->attach($translator, $postdata);

                            if (in_array($translator->pivot->step, [EventSteps::FINISHED, EventSteps::NONE])) {
                                $event->translators()->updateExistingPivot($memberID, ["step" => EventSteps::PRAY]);
                            }

                            $response["success"] = true;
                        } else {
                            $response["error"] = __("error_occurred", ["wrong parameters"]);
                        }
                    } else {
                        if ($action == "delete") {
                            if ($manageMode == "l2") {
                                $prevState = EventStates::L2_CHECK;
                                $nextState = EventStates::L3_RECRUIT;
                                $finishedState = EventStates::L2_CHECKED;
                            } elseif ($manageMode == "l3") {
                                $prevState = EventStates::L3_CHECK;
                                $nextState = EventStates::COMPLETE;
                                $finishedState = EventStates::COMPLETE;
                            } else {
                                $prevState = EventStates::TRANSLATING;
                                if (Tools::isHelp($mode)) {
                                    if ($mode == "tn") {
                                        $nextState = EventStates::L3_RECRUIT;
                                    } else {
                                        $nextState = EventStates::COMPLETE;
                                    }
                                } else {
                                    $nextState = EventStates::L2_RECRUIT;
                                }
                                $finishedState = EventStates::TRANSLATED;
                            }

                            if ($confirm < AssignChapterConfirm::EVENT_COMPLETE && EventStates::enum($event->state) >= EventStates::enum($finishedState)) {
                                $response["confirm"] = AssignChapterConfirm::EVENT_COMPLETE;
                                $response["message"] = __("event_finished_error");
                                echo json_encode($response);
                                return;
                            }

                            if (EventStates::enum($event->state) >= EventStates::enum($nextState) && $manageMode != "l3") {
                                $response["error"] = __("event_finished_not_possible_error");
                                echo json_encode($response);
                                return;
                            }

                            $event->update(["state" => $prevState]);

                            $translations = $event->translations()->where("chapter", $chapter)->get();

                            // Check if chapter has translations
                            $hasTranslations = $this->checkHasTranslations($translations, $manageMode);

                            if (!$hasTranslations || $confirm == AssignChapterConfirm::ASSIGN) {
                                if ($manageMode == "l1") {
                                    if ($data["chapters"][$chapter]["memberID"] == $memberID) {
                                        // Detaching removes all the chapters of the user
                                        // To fix, tables should be refactored
                                        //$event->translatorsWithChapters()->detach($translator, ["chapter" => $chapter]);

                                        $this->eventModel->removeChapter([
                                            "eventID" => $eventID,
                                            "memberID" => $memberID,
                                            "chapter" => $chapter]);
                                        $data["chapters"][$chapter] = [];

                                        $trPostData = [];

                                        $translator = $event->translators->find($memberID);
                                        $newChapters = $translator->chapters->filter(function($chapter) use($eventID) {
                                            return $chapter->eventID == $eventID && !$chapter->done;
                                        });

                                        $event->translations()->where("chapter", $chapter)->delete();
                                        $event->comments()->where("chapter", $chapter)->where("level", 1)->delete();

                                        $verbCheck = $translator->pivot->verbCheck ? json_decode($translator->pivot->verbCheck, true) : [];
                                        $peerCheck = $translator->pivot->peerCheck ? json_decode($translator->pivot->peerCheck, true) : [];
                                        $kwCheck = $translator->pivot->kwCheck ? json_decode($translator->pivot->kwCheck, true) : [];
                                        $crCheck = $translator->pivot->crCheck ? json_decode($translator->pivot->crCheck, true) : [];
                                        $otherCheck = $translator->pivot->otherCheck ? json_decode($translator->pivot->otherCheck, true) : [];
                                        if (array_key_exists($chapter, $verbCheck)) {
                                            unset($verbCheck[$chapter]);
                                            $trPostData["verbCheck"] = json_encode($verbCheck);
                                        }
                                        if (array_key_exists($chapter, $peerCheck)) {
                                            unset($peerCheck[$chapter]);
                                            $trPostData["peerCheck"] = json_encode($peerCheck);
                                        }
                                        if (array_key_exists($chapter, $kwCheck)) {
                                            unset($kwCheck[$chapter]);
                                            $trPostData["kwCheck"] = json_encode($kwCheck);
                                        }
                                        if (array_key_exists($chapter, $crCheck)) {
                                            unset($crCheck[$chapter]);
                                            $trPostData["crCheck"] = json_encode($crCheck);
                                        }
                                        if (array_key_exists($chapter, $otherCheck)) {
                                            unset($otherCheck[$chapter]);
                                            $trPostData["otherCheck"] = json_encode($otherCheck);
                                        }

                                        // Clear translator data to default if current chapter was removed
                                        // Change translator's step to NONE when no chapter is assigned to him
                                        if ($translator->pivot->currentChapter == $chapter || $newChapters->count() == 0) {
                                            $trPostData["step"] = $newChapters->count() == 0 ? EventSteps::NONE : EventSteps::PRAY;
                                            $trPostData["currentChapter"] = $mode == "tn" ? -1 : 0;
                                            $trPostData["currentChunk"] = 0;
                                        }

                                        if (!empty($trPostData)) {
                                            $event->translators()->updateExistingPivot($memberID, $trPostData);
                                        }

                                        $response["success"] = true;
                                    } else {
                                        $response["error"] = __("error_occurred", array("wrong parameters"));
                                    }
                                } else if ($manageMode == "l2") {
                                    if ($data["chapters"][$chapter]["l2memberID"] == $memberID) {
                                        if ($hasTranslations) {
                                            $event->comments()
                                                ->where("chapter", $chapter)->where("level", 2)
                                                ->delete();

                                            $translations->each(function($item) {
                                                $verses = $item->translatedVerses ? json_decode($item->translatedVerses, true) : [];
                                                if (array_key_exists(EventMembers::L2_CHECKER, $verses)) {
                                                    $verses[EventMembers::L2_CHECKER]["verses"] = [];
                                                }
                                                $item->translatedVerses = json_encode($verses);
                                                $item->save();
                                            });
                                        }

                                        $this->eventModel->updateChapter([
                                            "l2memberID" => 0,
                                            "l2chID" => 0,
                                            "l2checked" => false
                                        ], [
                                            "eventID" => $eventID,
                                            "chapter" => $chapter
                                        ]);
                                        $data["chapters"][$chapter]["l2memberID"] = 0;
                                        $data["chapters"][$chapter]["l2chID"] = 0;

                                        $trPostData = [];

                                        $checker = $event->checkersL2->find($memberID);
                                        $newChapters = $checker->chaptersL2->filter(function($chapter) use($eventID) {
                                            return $chapter->eventID == $eventID && !$chapter->l2checked;
                                        });

                                        // Clear checker's data to default if current chapter was removed
                                        // Change checker's step to NONE when no chapter is assigned to him
                                        if ($checker->currentChapter == $chapter || $newChapters->count() == 0) {
                                            $trPostData["step"] = $newChapters->count() == 0 ? EventSteps::NONE : EventSteps::PRAY;
                                            $trPostData["currentChapter"] = 0;
                                        }

                                        $peerCheck = $checker->pivot->peerCheck ? json_decode($checker->pivot->peerCheck, true) : [];
                                        $kwCheck = $checker->pivot->kwCheck ? json_decode($checker->pivot->kwCheck, true) : [];
                                        $crCheck = $checker->pivot->crCheck ? json_decode($checker->pivot->crCheck, true) : [];
                                        if (array_key_exists($chapter, $peerCheck)) {
                                            unset($peerCheck[$chapter]);
                                            $trPostData["peerCheck"] = json_encode($peerCheck);
                                        }
                                        if (array_key_exists($chapter, $kwCheck)) {
                                            unset($kwCheck[$chapter]);
                                            $trPostData["kwCheck"] = json_encode($kwCheck);
                                        }
                                        if (array_key_exists($chapter, $crCheck)) {
                                            unset($crCheck[$chapter]);
                                            $trPostData["crCheck"] = json_encode($crCheck);
                                        }

                                        if (!empty($trPostData)) {
                                            $event->checkersL2()->updateExistingPivot($memberID, $trPostData);
                                        }

                                        $response["success"] = true;
                                    } else {
                                        $response["error"] = __("error_occurred", array("wrong parameters"));
                                    }
                                } else if ($manageMode == "l3") {
                                    if ($data["chapters"][$chapter]["l3memberID"] == $memberID) {
                                        if ($hasTranslations) {
                                            $event->comments()
                                                ->where("chapter", $chapter)->where("level", 3)
                                                ->delete();

                                            $translations->each(function($item) {
                                                $verses = $item->translatedVerses ? json_decode($item->translatedVerses, true) : [];
                                                if (array_key_exists(EventMembers::L3_CHECKER, $verses)) {
                                                    $verses[EventMembers::L3_CHECKER]["verses"] = [];
                                                }
                                                $item->translatedVerses = json_encode($verses);
                                                $item->save();
                                            });
                                        }

                                        $this->eventModel->updateChapter([
                                            "l3memberID" => 0,
                                            "l3chID" => 0,
                                            "l3checked" => false
                                        ], [
                                            "eventID" => $eventID,
                                            "chapter" => $chapter
                                        ]);
                                        $data["chapters"][$chapter]["l3memberID"] = 0;
                                        $data["chapters"][$chapter]["l3chID"] = 0;

                                        $trPostData = [];

                                        $checker = $event->checkersL3->find($memberID);
                                        $newChapters = $checker->chaptersL3->filter(function($chapter) use($eventID) {
                                            return $chapter->eventID == $eventID && !$chapter->l3checked;
                                        });

                                        // Clear checker's data to default if current chapter was removed
                                        // Change checker's step to NONE when no chapter is assigned to him
                                        if ($checker->currentChapter == $chapter || $newChapters->count() == 0) {
                                            $trPostData["step"] = $newChapters->count() == 0 ? EventCheckSteps::NONE : EventCheckSteps::PRAY;
                                            $trPostData["currentChapter"] = $event->project->bookProject == "tn" ? -1 : 0;
                                        }

                                        $peerCheck = $checker->pivot->peerCheck ? json_decode($checker->pivot->peerCheck, true) : [];
                                        if (array_key_exists($chapter, $peerCheck)) {
                                            unset($peerCheck[$chapter]);
                                            $trPostData["peerCheck"] = json_encode($peerCheck);
                                        }

                                        if (!empty($trPostData)) {
                                            $event->checkersL3()->updateExistingPivot($memberID, $trPostData);
                                        }

                                        $response["success"] = true;
                                    } else {
                                        $response["error"] = __("error_occurred", array("wrong parameters"));
                                    }
                                }
                            } else {
                                $response["message"] = __("event_translating_exit_error");
                                $response["confirm"] = AssignChapterConfirm::ASSIGN;
                            }
                        } else if ($action == "add" && $manageMode == "l2") {
                            if ($data["chapters"][$chapter]["l2memberID"] == 0) {
                                $checker = $event->checkersL2->find($memberID);
                                $checkerL2 = $checker->checkersL2->where("eventID", $eventID, false)->first();

                                $postdata = [
                                    "l2chID" => $checkerL2->l2chID,
                                    "l2memberID" => $checker->memberID
                                ];

                                $this->eventModel->updateChapter($postdata, [
                                    "eventID" => $eventID,
                                    "chapter" => $chapter
                                ]);

                                if ($checkerL2->step == EventCheckSteps::NONE) {
                                    $event->checkersL2()->updateExistingPivot($memberID, ["step" => EventCheckSteps::PRAY]);
                                }
                                $response["success"] = true;
                            } else {
                                $response["error"] = __("chapter_already_assigned_error");
                            }
                        } else if ($action == "add" && $manageMode == "l3") {
                            if ($data["chapters"][$chapter]["l3memberID"] == 0) {
                                $checker = $event->checkersL3->find($memberID);
                                $checkerL3 = $checker->checkersL3->where("eventID", $eventID, false)->first();

                                $postdata = [
                                    "l3chID" => $checkerL3->l3chID,
                                    "l3memberID" => $checker->memberID
                                ];

                                $this->eventModel->updateChapter($postdata, [
                                    "eventID" => $eventID,
                                    "chapter" => $chapter
                                ]);

                                if ($checkerL3->step == EventCheckSteps::NONE) {
                                    $event->checkersL3()->updateExistingPivot($memberID, ["step" => EventCheckSteps::PRAY]);
                                }
                                $response["success"] = true;
                            } else {
                                $response["error"] = __("chapter_already_assigned_error");
                            }
                        } else {
                            $response["error"] = __("chapter_already_assigned_error");
                        }
                    }
                } else {
                    $response["error"] = __("not_enough_rights_error");
                }
            } else {
                $response["error"] = __("error_occurred", array("wrong parameters"));
            }
        } else {
            $response["error"] = __("error_occurred", array("wrong parameters"));
        }

        echo json_encode($response);
    }

    public function addEventMember()
    {
        $data["errors"] = array();

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST['eventID']) && $_POST['eventID'] != "" ? $_POST['eventID'] : null;
        $userType = isset($_POST['userType']) && $_POST['userType'] != "" ? $_POST['userType'] : null;
        $memberID = isset($_POST['memberID']) && $_POST['memberID'] != "" ? $_POST['memberID'] : null;

        $appliedMember = $this->memberRepo->get($memberID);

        if (!$appliedMember) {
            $error[] = __('wrong_event_parameters');
            echo json_encode(array("error" => $error));
            return;
        }

        if ($eventID == null) {
            $error[] = __('wrong_event_parameters');
            echo json_encode(array("error" => $error));
            return;
        }

        if ($userType == null || !preg_match("/^(" . EventMembers::TRANSLATOR . "|" . EventMembers::L2_CHECKER . "|" . EventMembers::L3_CHECKER . ")$/", $userType)) {
            $error[] = __("wrong_event_parameters");
            echo json_encode(array("error" => $error));
            return;
        }

        /*if ($userType == EventMembers::L2_CHECKER || $userType == EventMembers::L3_CHECKER) {
            $education = $appliedMember->profile->education ? json_decode($appliedMember->profile->education) : [];
            if (empty($education)) {
                $data["errors"][] = __("education_public");
            } else {
                foreach ($education as $item) {
                    if (!preg_match("/^(BA|MA|PHD)$/", $item)) {
                        $data["errors"][] = __("education_public");
                        break;
                    }
                }
            }

            $ed_area = $appliedMember->profile->ed_area ? json_decode($appliedMember->profile->ed_area) : [];
            if (empty($ed_area))
                $data["errors"][] = __("ed_area");
            else {
                foreach ($ed_area as $item) {
                    if (!preg_match("/^(Theology|Pastoral Ministry|Bible Translation|Exegetics)$/", $item)) {
                        $data["errors"][] = __("ed_area");
                        break;
                    }
                }
            }

            if (empty($appliedMember->profile->ed_place))
                $data["errors"][] = __("ed_place");

            if (empty($appliedMember->profile->hebrew_knwlg))
                $data["errors"][] = __("hebrew_knwlg");

            if (empty($appliedMember->profile->greek_knwlg))
                $data["errors"][] = __("greek_knwlg");

            $church_role = $appliedMember->profile->church_role ? json_decode($appliedMember->profile->church_role) : [];
            if (empty($church_role))
                $data["errors"][] = __("church_role_public");
            else {
                foreach ($church_role as $item) {
                    if (!preg_match("/^(Elder|Bishop|Pastor|Teacher|Denominational Leader|Seminary Professor)$/", $item)) {
                        $data["errors"][] = __("church_role_public");
                        break;
                    }
                }
            }
        }*/

        if (empty($data["errors"])) {
            $event = $this->eventRepo->get($eventID);

            if (!$event) {
                $error[] = __("event_notexist_error");
                echo json_encode(array("error" => $error));
                return;
            }

            if (!$this->isAdmin($event)) {
                $error[] = __("not_enough_rights_error");
                echo json_encode(array("error" => $error));
                return;
            }

            $mode = $event->project->bookProject;
            $exists = $event->translators->contains($appliedMember)
                || $event->checkersL2->contains($appliedMember)
                || $event->checkersL3->contains($appliedMember);

            switch ($userType) {
                case EventMembers::TRANSLATOR:
                    if (!$exists) {
                        $chapter = in_array($mode, ["tn"]) ? -1 : 0;
                        $trData = array(
                            "step" => EventSteps::NONE,
                            "currentChapter" => $chapter
                        );

                        $event->translators()->attach($appliedMember, $trData);

                        echo json_encode(array("success" => __("successfully_applied")));
                    } else {
                        $error[] = __("error_member_in_event");
                    }
                    break;

                case EventMembers::L2_CHECKER:
                    if (!$exists) {
                        $l2Data = array(
                            "step" => EventSteps::NONE
                        );
                        $event->checkersL2()->attach($appliedMember, $l2Data);

                        echo json_encode(array("success" => __("successfully_applied")));
                    } else {
                        $error[] = __("error_member_in_event");
                    }
                    break;

                case EventMembers::L3_CHECKER:
                    if (!$exists) {
                        $chapter = in_array($mode, ["tn"]) ? -1 : 0;
                        $l3Data = array(
                            "step" => EventSteps::NONE,
                            "currentChapter" => $chapter
                        );
                        $event->checkersL3()->attach($appliedMember, $l3Data);

                        echo json_encode(array("success" => __("successfully_applied")));
                    } else {
                        $error[] = __("error_member_in_event");
                    }
                    break;
            }

            if (isset($error)) {
                echo json_encode(array("error" => $error));
            }
        } else {
            $error[] = __('empty_profile_error');
            echo json_encode(array("error" => $error, "errors" => $data["errors"]));
        }
    }

    /**
     * Delete user from event
     */
    public function deleteEventMember()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $memberID = isset($_POST["memberID"]) && $_POST["memberID"] != "" ? (integer)$_POST["memberID"] : null;
        $manageMode = isset($_POST["manageMode"]) && $_POST["manageMode"] != "" ? $_POST["manageMode"] : "l1";

        $deleteMember = $this->memberRepo->get($memberID);

        if ($eventID !== null && $memberID != null) {
            $event = $this->eventRepo->get($eventID);
            if ($event) {
                if ($this->isAdmin($event)) {
                    $hasChapter = false;
                    $chapters = $this->eventModel->getChapters($event->eventID);

                    foreach ($chapters as $chap) {
                        $index = "memberID";
                        if ($manageMode == "l2")
                            $index = "l2memberID";
                        if ($manageMode == "l3")
                            $index = "l3memberID";
                        if ($chap[$index] == $memberID) {
                            $hasChapter = true;
                            break;
                        }
                    }

                    if (!$hasChapter) {
                        if ($manageMode == "l2")
                            $event->checkersL2()->detach($deleteMember);
                        else if ($manageMode == "l3")
                            $event->checkersL3()->detach($deleteMember);
                        else
                            $event->translators()->detach($deleteMember);
                        $response["success"] = true;
                    } else {
                        $response["error"] = __("translator_has_chapter");
                    }
                } else {
                    $response["error"] = __("not_enough_rights_error");
                }
            } else {
                $response["error"] = __("error_occurred", array("wrong parameters"));
            }
        } else {
            $response["error"] = __("error_occurred", array("wrong parameters"));
        }

        echo json_encode($response);
    }

    public function createWordsGroup()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $group = isset($_POST["group"]) ? (array)$_POST["group"] : [];

        $group = array_filter($group, function ($elm) {
            return $elm != "";
        });

        if ($eventID && !empty($group)) {
            $event = $this->eventRepo->get($eventID);

            if ($event) {
                if (!$this->isAdmin($event)) {
                    $response["error"] = __("not_enough_rights_error");
                    echo json_encode($response);
                    return;
                }

                $groups = $this->eventModel->getWordGroups(["eventID" => $eventID]);

                $testGroup = [];
                foreach ($groups as $gr) {
                    $elm = $gr->words ? json_decode($gr->words, true) : [];
                    $testGroup = Arrays::append($testGroup, $elm);
                }

                if (empty(array_intersect($group, $testGroup))) {
                    $created = $this->eventModel->createWordGroup([
                        "eventID" => $eventID,
                        "words" => json_encode($group)
                    ]);

                    if ($created) {
                        $response["success"] = true;
                    }
                } else {
                    $response["success"] = false;
                    $response["error"] = __("words_present_in_group_error");
                }
            } else {
                $response["success"] = false;
                $response["error"] = __("error_occurred", array("wrong parameters"));
            }
        } else {
            $response["success"] = false;
            $response["error"] = __("error_occurred", array("wrong parameters"));
        }

        echo json_encode($response);
    }

    public function deleteWordsGroup()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) ? (integer)$_POST["eventID"] : null;
        $groupID = isset($_POST["groupID"]) ? (integer)$_POST["groupID"] : null;

        if ($eventID && $groupID) {
            $event = $this->eventRepo->get($eventID);

            if ($event) {
                if (!$this->isAdmin($event)) {
                    $response["error"] = __("not_enough_rights_error");
                    echo json_encode($response);
                    return;
                }

                $chapter = $this->eventModel->getChapters($eventID, null, $groupID);

                if (empty($chapter)) {
                    $deleted = $this->eventModel->deleteWordGroup([
                        "groupID" => $groupID
                    ]);

                    if ($deleted) {
                        $response["success"] = true;
                    } else {
                        $response["success"] = false;
                        $response["error"] = __("error_occurred", array("wrong parameters"));
                    }
                } else {
                    $response["success"] = false;
                    $response["error"] = __("user_has_group_error");
                }
            } else {
                $response["success"] = false;
                $response["error"] = __("error_occurred", array("wrong parameters"));
            }
        } else {
            $response["success"] = false;
            $response["error"] = __("error_occurred", array("wrong parameters"));
        }

        echo json_encode($response);
    }

    public function getEventMembers()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $memberIDs = isset($_POST["memberIDs"]) && $_POST["memberIDs"] != "" ? (array)$_POST["memberIDs"] : [];
        $manageMode = isset($_POST["manageMode"]) && $_POST["manageMode"] != "" ? $_POST["manageMode"] : "l1";

        if ($eventID !== null && $memberIDs !== null) {
            $event = $this->eventRepo->get($eventID);

            if ($event) {
                if ($event->admins->contains($this->member)
                    || $event->project->admins->contains($this->member)
                    || $event->project->gatewayLanguage->admins->contains($this->member)) {

                    if ($manageMode == "l1")
                        $members = $event->translators;
                    else if ($manageMode == "l2")
                        $members = $event->checkersL2;
                    else if ($manageMode == "l3")
                        $members = $event->checkersL3;
                    else
                        $members = new Collection();

                    $response["members"] = $members->except($memberIDs)->getDictionary();
                    $response["success"] = true;
                } else {
                    $response["error"] = __("not_enough_rights_error");
                }
            } else {
                $response["error"] = __("error_occurred", array("wrong parameters"));
            }
        } else {
            $response["error"] = __("error_occurred", array("wrong parameters"));
        }

        echo json_encode($response);
    }

    public function sendUserEmail() {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $memberID = isset($_POST["memberID"]) && $_POST["memberID"] != "" ? (integer)$_POST["memberID"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : null;

        if ($eventID && $memberID) {
            $event = $this->eventRepo->get($eventID);

            if ($event) {
                if ($event->admins->contains($this->member)
                    || $event->project->admins->contains($this->member)
                    || $event->project->gatewayLanguage->admins->contains($this->member)) {

                    $member = $this->memberRepo->get($memberID);

                    if ($member) {
                        if ($chapter) {
                            $this->sendChapterAssignmentNotif($event, $member, $chapter);
                        } else {
                            $this->sendProjectAssignmentNotif($event, $member);
                        }

                        $response["success"] = true;
                    }
                } else {
                    $response["error"] = __("not_enough_rights_error");
                }
            } else {
                $response["error"] = __("error_occurred", array("wrong parameters"));
            }
        }

        echo json_encode($response);
    }

    private function isAdmin($event) {
        return $event->admins->contains($this->member)
            || $event->project->admins->contains($this->member)
            || $event->project->gatewayLanguage->admins->contains($this->member);
    }

    private function sendProjectAssignmentNotif($event, $user) {
        if(Config::get("app.type") == "remote") {
            $data = [
                "book" => $event->bookInfo->name,
                "language" => $event->project->gatewayLanguage->language->langName,
                "project" => $event->project->bookProject,
                "target" => $event->project->targetLanguage->langName,
            ];
            AMQPMailer::sendView(
                "Emails/Manage/ProjectAssignmentNotification",
                $data,
                [$user->email],
                __("project_assignment_notif")
            );
        }
    }

    private function sendChapterAssignmentNotif($event, $user, $chapter) {
        if(Config::get("app.type") == "remote") {
            $data = [
                "book" => $event->bookInfo->name,
                "language" => $event->project->gatewayLanguage->language->langName,
                "project" => $event->project->bookProject,
                "target" => $event->project->targetLanguage->langName,
                "chapter" => $chapter
            ];
            AMQPMailer::sendView(
                "Emails/Manage/ChapterAssignmentNotification",
                $data,
                [$user->email],
                __("chapter_assignment_notif")
            );
        }
    }
}
