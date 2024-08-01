<?php
/**
 * Created by mXaln
 */

namespace App\Controllers;

use App\Data\NotificationMapper;
use App\Domain\AnyL3Progress;
use App\Domain\RenderNotifications;
use App\Domain\ResourceProgress;
use App\Domain\OdbSunProgress;
use App\Domain\RadioProgress;
use App\Domain\ScriptureRevisionProgress;
use App\Domain\ScriptureProgress;
use App\Domain\SunRevisionProgress;
use App\Domain\SunProgress;
use App\Domain\TnProgress;
use App\Domain\TqProgress;
use App\Domain\TwProgress;
use App\Models\NewsModel;
use App\Models\SailDictionaryModel;
use App\Repositories\Event\IEventRepository;
use App\Repositories\Member\IMemberRepository;
use App\Repositories\Resources\IResourcesRepository;
use Helpers\Arrays;
use Support\Facades\View;
use Config\Config;
use Helpers\Url;
use Helpers\Session;
use App\Core\Controller;
use App\Models\EventsModel;

class InformationController extends Controller {
    private $eventModel;
    private $newsModel;
    private $_saildictModel;
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
            $this->_saildictModel = new SailDictionaryModel();
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

    public function information($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if (!in_array($event->project->bookProject, ["ulb", "udb"])) {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, ScriptureProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;

            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $member;
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/L1/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/L1/GetInfo")
                ->shares("data", $data)
                ->shares("event", $event)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationNotes($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "tn") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, TnProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Notes/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/Notes/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationQuestions($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "tq") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, TqProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Questions/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/Questions/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationWords($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "tw") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, TwProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/TWords/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/TWords/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationObs($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "obs") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, ResourceProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Obs/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/Obs/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationBc($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "bc") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, ResourceProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Bc/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/Bc/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationBca($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "bca") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, ResourceProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Bca/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/Bca/GetInfo")
                ->shares("data", $data)
                ->shares("event", $event)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationRevision($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, ScriptureRevisionProgress::calculateEventProgress($event));
            $members = $data["members"];

            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;

            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
            $data["revisionMode"] = $event->revisionMode;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Revision/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["revisionMode"] = $data["revisionMode"];
            $response["html"] = View::make("Events/Revision/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationSunRevision($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, SunRevisionProgress::calculateEventProgress($event));
            $members = $data["members"];

            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;

            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/RevisionSun/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/RevisionSun/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationReview($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, AnyL3Progress::calculateEventProgress($event));
            $members = $data["members"];

            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;

            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/InformationL3')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/GetInfoL3")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationSun($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "sun") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, SunProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/SUN/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/SUN/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationOdbSun($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "sun") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, OdbSunProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;

            $data["odb"] = $this->resourcesRepo->getOtherResource(
                $event->project->sourceLangID,
                "odb",
                $event->bookCode
            );
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/ODBSUN/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/ODBSUN/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    public function informationRadio($eventID)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response = ["success" => false];
        }

        $event = $this->eventRepo->get($eventID);

        $data["menu"] = 1;
        $data["isAdmin"] = false;

        if ($event) {
            if ($event->project->bookProject != "rad") {
                Url::redirect("events/");
            }

            if (!$this->canViewInfo($event)) {
                if (!$isXhr)
                    $error[] = __("empty_or_not_permitted_event_error");
                else {
                    $response["errorType"] = "empty_no_permission";
                    echo json_encode($response);
                    exit;
                }
            }

            $data["isAdmin"] = $this->isAdmin($event);
        } else {
            if (!$isXhr)
                $error[] = __("empty_or_not_permitted_event_error");
            else {
                $response["errorType"] = "empty_no_permission";
                echo json_encode($response);
                exit;
            }
        }

        if (!isset($error)) {
            $data = array_merge($data, RadioProgress::calculateEventProgress($event));
            $members = $data["members"];
            $admins = array_keys($event->admins->getDictionary());

            $empty = array_fill(0, sizeof($admins), "");
            $adminsArr = array_combine($admins, $empty);

            $members += $adminsArr;
            $membersData = $this->memberRepo->all()->filter(function($m) use ($members) {
                return array_key_exists($m->memberID, $members);
            });

            foreach ($membersData as $member) {
                $members[$member->memberID] = [];
                $members[$member->memberID]["userName"] = $member->userName;
                $members[$member->memberID]["name"] = $member->firstName . " " . mb_substr($member->lastName, 0, 1) . ".";
            }

            foreach ($members as $key => $member) {
                if (!is_numeric($key) && $key != "na") {
                    $name = $members[$key];
                    $members[$key] = [];
                    $members[$key]["userName"] = $key;
                    $members[$key]["name"] = $name;
                }
            }

            $members["na"] = __("not_available");
            $members = array_filter($members);

            $data["admins"] = $admins;
            $data["members"] = $members;

            $data["rad"] = $this->resourcesRepo->getOtherResource(
                $event->project->sourceLangID,
                "rad",
                $event->bookCode
            );
        }

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        if (!$isXhr) {
            return View::make('Events/Radio/Information')
                ->shares("title", __("event_info"))
                ->shares("data", $data)
                ->shares("event", $event)
                ->shares("error", @$error);
        } else {
            $this->layout = "dummy";
            $response["success"] = true;
            $response["progress"] = $data["overall_progress"];
            $response["admins"] = $data["admins"];
            $response["members"] = $data["members"];
            $response["html"] = View::make("Events/Radio/GetInfo")
                ->shares("data", $data)
                ->renderContents();

            echo json_encode($response);
        }
    }

    private function canViewInfo($event) {
        $isAdmin = $this->isAdmin($event);

        $isParticipant = $event->translators->contains($this->member)
            || $event->checkersL2->contains($this->member)
            || $event->checkersL3->contains($this->member);

        return ($isAdmin || $isParticipant) && $event->state != "started";
    }

    private function isAdmin($event) {
        return $event->admins->contains($this->member)
            || $event->project->admins->contains($this->member)
            || $event->project->gatewayLanguage->admins->contains($this->member);
    }
}
