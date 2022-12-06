<?php
/**
 * Created by mXaln
 */

namespace App\Controllers;

use App\Data\Notification;
use App\Data\NotificationMapper;
use App\Domain\RenderNotifications;
use App\Helpers\EventUtil;
use App\Models\NewsModel;
use App\Models\ApiModel;
use App\Models\SailDictionaryModel;
use App\Repositories\Event\IEventRepository;
use App\Repositories\Member\IMemberRepository;
use App\Repositories\Resources\IResourcesRepository;
use Helpers\Arrays;
use Helpers\Constants\InputMode;
use Helpers\Constants\NotificationType;
use Helpers\Constants\OdbSections;
use Helpers\Constants\RevisionMode;
use Helpers\Markdownify\Converter;
use Helpers\Tools;
use Support\Facades\View;
use Config\Config;
use Helpers\Url;
use Helpers\Gump;
use Helpers\Session;
use App\Core\Controller;
use App\Models\EventsModel;
use App\Models\MembersModel;
use App\Models\TranslationsModel;
use Helpers\Constants\EventSteps;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventStates;
use Helpers\Constants\EventMembers;
use Support\Facades\Language;
use AMQPMailer;

class EventsController extends Controller {
    private $eventModel;
    private $translationModel;
    private $sailDictModel;
    private $apiModel;
    private $newsModel;
    private $membersModel;
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

        if (preg_match("/^\\/events\\/rpc\\/get_saildict/", $_SERVER["REQUEST_URI"])) {
            $this->sailDictModel = new SailDictionaryModel();
            return;
        }

        if (!Session::get('memberID')
            && !preg_match("/^\\/events\\/demo|\\/events\\/faq/", $_SERVER["REQUEST_URI"])) {
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
            $this->sailDictModel = new SailDictionaryModel();
            $this->apiModel = new ApiModel();
            $this->newsModel = new NewsModel();
            $this->membersModel = new MembersModel();

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

    /**
     * Show member's dashboard view
     * @return mixed
     */
    public function index()
    {
        $data["menu"] = 1;

        if (Session::get("isBookAdmin")) {
            $myFacilitatorEvents = $this->member->adminEvents;
            $data["myFacilitatorEventsInProgress"] = [];
            $data["myFacilitatorEventsFinished"] = [];

            foreach ($myFacilitatorEvents as $myFacilitatorEvent) {
                if ($myFacilitatorEvent->state == EventStates::TRANSLATED
                    || $myFacilitatorEvent->state == EventStates::L2_CHECKED
                    || $myFacilitatorEvent->state == EventStates::COMPLETE)
                    $data["myFacilitatorEventsFinished"][] = $myFacilitatorEvent;
                else
                    $data["myFacilitatorEventsInProgress"][] = $myFacilitatorEvent;
            }
        }

        $data["myTranslatorEvents"] = $this->eventModel->getMemberEvents(
            Session::get("memberID"),
            null,
            null,
            true,
            false,
            false
        );

        $data["myRevisionEvents"] = $this->eventModel->getRevisionMemberEvents(
            Session::get("memberID"),
            null,
            null,
            true,
            false
        );
        $sunRevisionEditors = $this->eventModel->getMemberEventsForSunRevisionChecker(Session::get("memberID"));
        $data["myRevisionEvents"] = Arrays::append(
            $data["myRevisionEvents"],
            $sunRevisionEditors
        );

        $data["myOtherEvents"] = [];
        $notesEditors = $this->eventModel->getMemberEventsForNotes(
            Session::get("memberID"), null, null, null, "edit"
        );
        $otherEditors = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"), null, null, null, "edit"
        );
        $data["myOtherEvents"] = Arrays::append(
            $data["myOtherEvents"],
            $notesEditors
        );
        $data["myOtherEvents"] = Arrays::append(
            $data["myOtherEvents"],
            $otherEditors
        );

        $data["myCheckerL1Events"] = $this->eventModel->getMemberEventsForChecker(Session::get("memberID"));
        $notesCheckers = $this->eventModel->getMemberEventsForNotes(
            Session::get("memberID"), null, null, null, "check"
        );
        $sunCheckers = $this->eventModel->getMemberEventsForCheckerSun(Session::get("memberID"));
        $otherCheckers = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"), null, null, null, "check"
        );
        $radioCheckers = $this->eventModel->getMemberEventsForRadio(Session::get("memberID"));

        $data["myCheckerL1Events"] = Arrays::append(
            $data["myCheckerL1Events"],
            $notesCheckers);
        $data["myCheckerL1Events"] = Arrays::append(
            $data["myCheckerL1Events"],
            $sunCheckers);
        $data["myCheckerL1Events"] = Arrays::append(
            $data["myCheckerL1Events"],
            $otherCheckers);
        $data["myCheckerL1Events"] = Arrays::append(
            $data["myCheckerL1Events"],
            $radioCheckers);

        $data["myCheckerL2Events"] = $this->eventModel->getMemberEventsForRevisionChecker(Session::get("memberID"));

        $data["myCheckerL3Events"] = $this->eventModel->getMemberEventsForCheckerL3(Session::get("memberID"));

        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        return View::make('Events/Index')
            ->shares("title", __("welcome_title"))
            ->shares("data", $data);
    }

    /**
     * @param $eventID
     * @return mixed
     */
    public function translator($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);
        $data["next_step"] = EventSteps::PRAY;

        if (!empty($data["event"])) {
            if (!in_array($data["event"][0]->bookProject, ["ulb", "udb"])) {
                Url::redirect("events/translator-" . $data["event"][0]->bookProject . "/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $nextStep = in_array($data["event"][0]->inputMode, [InputMode::SCRIPTURE_INPUT, InputMode::SPEECH_TO_TEXT])
                                    ? EventSteps::MULTI_DRAFT : EventSteps::CONSUME;
                                $postdata = [
                                    "step" => $nextStep,
                                    "currentChapter" => $sourceText["currentChapter"],
                                    "currentChunk" => $sourceText["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating this book
                        $data["event"][0]->justStarted = $data["event"][0]->verbCheck == "";
                        $data["next_step"] = in_array($data["event"][0]->inputMode, [InputMode::SCRIPTURE_INPUT, InputMode::SPEECH_TO_TEXT])
                            ? "multi-draft_input_mode" : EventSteps::CONSUME;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    // Scripture Input / Speech2Text Step 1
                    case EventSteps::MULTI_DRAFT:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = [];

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $arr["firstvs"] = $tv->firstvs;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            if (isset($_POST["confirm_step"])) {
                                if (isset($translation) && sizeof($translation) == $sourceText["totalVerses"]) {
                                    // Check for empty verses
                                    $empty = array_filter($translation, function ($elm) {
                                        $key = key($elm[EventMembers::TRANSLATOR]["verses"]);
                                        return empty($elm[EventMembers::TRANSLATOR]["verses"][$key]);
                                    });

                                    if (empty($empty)) {
                                        $chunks = array_map(function($elm) {
                                            return [key($elm[EventMembers::TRANSLATOR]["verses"])];
                                        }, $translation);

                                        $this->eventModel->updateChapter(
                                            ["chunks" => json_encode($chunks)],
                                            [
                                                "eventID" => $data["event"][0]->eventID,
                                                "chapter" => $data["event"][0]->currentChapter
                                            ]
                                        );

                                        $postdata = [
                                            "step" => EventSteps::SELF_CHECK
                                        ];

                                        $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                        Url::redirect('events/translator/' . $data["event"][0]->eventID);
                                    } else {
                                        $error[] = __("empty_draft_verses_error");
                                    }
                                } else {
                                    $error[] = __("not_equal_verse_markers");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/Input')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONSUME:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = ["step" => EventSteps::VERBALIZE];

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::VERBALIZE;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::VERBALIZE:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        $data["event"][0]->checkerName = null;
                        $verbCheck = (array)json_decode($data["event"][0]->verbCheck, true);
                        $checkDone = false;
                        if (array_key_exists($data["event"][0]->currentChapter, $verbCheck)) {
                            $memberID = $verbCheck[$data["event"][0]->currentChapter]["memberID"];
                            $checkDone = $verbCheck[$data["event"][0]->currentChapter]["done"] > 0;
                            if (!is_numeric($memberID)) {
                                $data["event"][0]->checkerName = $memberID;
                            } else {
                                $member = $this->memberRepo->get($memberID);
                                $data["event"][0]->checkerName = $member->firstName
                                    . " " . mb_substr($member->lastName, 0, 1) . ".";
                            }
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if ($checkDone) {
                                    $postdata = ["step" => EventSteps::CHUNKING];
                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("verb_checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::CHUNKING;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/Verbalize')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CHUNKING:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $_POST = Gump::xss_clean($_POST);

                                $chunks = $_POST["chunks_array"] ?? "";
                                $chunks = (array)json_decode($chunks);
                                if ($this->apiModel->testChunks($chunks, $sourceText["totalVerses"])) {
                                    if ($this->eventModel->updateChapter(["chunks" => json_encode($chunks)], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter])) {
                                        $this->eventModel->updateTranslator(["step" => EventSteps::READ_CHUNK], ["trID" => $data["event"][0]->trID]);
                                        Url::redirect('events/translator/' . $data["event"][0]->eventID);
                                    } else {
                                        $error[] = __("error_occurred", "Unknown error");
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::BLIND_DRAFT;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/Chunking')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::READ_CHUNK:
                        $sourceText = $this->getScriptureSourceText($data, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $this->eventModel->updateTranslator(["step" => EventSteps::BLIND_DRAFT], ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/ReadChunk')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::BLIND_DRAFT:
                        $sourceText = $this->getScriptureSourceText($data, true);
                        $translationData = [];

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter,
                                    $data["event"][0]->currentChunk
                                );

                                if (!empty($translationData)) {
                                    $verses = json_decode($translationData[0]->translatedVerses, true);
                                    $data["blind"] = $verses[EventMembers::TRANSLATOR]["blind"];
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            $draft = $_POST["draft"] ?? "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim($draft) != "") {
                                    if (empty($translationData)) {
                                        $translationVerses = [
                                            EventMembers::TRANSLATOR => [
                                                "blind" => trim($draft),
                                                "verses" => []
                                            ],
                                            EventMembers::L2_CHECKER => [
                                                "verses" => array()
                                            ],
                                            EventMembers::L3_CHECKER => [
                                                "verses" => array()
                                            ],
                                        ];

                                        $encoded = json_encode($translationVerses);
                                        $json_error = json_last_error();

                                        if ($json_error == JSON_ERROR_NONE) {
                                            $trData = [
                                                "projectID" => $data["event"][0]->projectID,
                                                "eventID" => $data["event"][0]->eventID,
                                                "trID" => $data["event"][0]->trID,
                                                "targetLang" => $data["event"][0]->targetLang,
                                                "bookProject" => $data["event"][0]->bookProject,
                                                "sort" => $data["event"][0]->sort,
                                                "bookCode" => $data["event"][0]->bookCode,
                                                "chapter" => $data["event"][0]->currentChapter,
                                                "chunk" => $data["event"][0]->currentChunk,
                                                "firstvs" => $sourceText["chunk"][0],
                                                "translatedVerses" => $encoded,
                                                "dateCreate" => date('Y-m-d H:i:s')
                                            ];

                                            $this->translationModel->createTranslation($trData);
                                        }
                                    }

                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    // If chapter is finished go to SELF_EDIT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::READ_CHUNK;
                                    }

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("empty_draft_verses_error");
                                }
                            }
                        }

                        if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                            $data["next_step"] = "continue_alt";
                        } else {
                            $data["next_step"] = EventSteps::SELF_CHECK;
                        }

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/BlindDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);
                        $nextChapter = 0;

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $arr["firstvs"] = $tv->firstvs;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                // Get next chapter if it exists
                                $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));
                                if (!empty($nextChapterDB && isset($nextChapterDB[1])))
                                    $nextChapter = $nextChapterDB[1]->chapter;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            $submitStep = isset($_POST["submitStep"]) && $_POST["submitStep"];
                            $postdata = [];
                            $chapterLink = $submitStep ? "/" . $data["event"][0]->currentChapter : "";

                            if (isset($_POST["confirm_step"])) {
                                if (isset($translationData)) {
                                    foreach ($translationData as $tv) {
                                        $this->translationModel->updateTranslation([
                                            "translateDone" => true
                                        ], ["tID" => $tv->tID]);
                                    }
                                }

                                $postdata["step"] = EventSteps::NONE;
                                $postdata["currentChapter"] = 0;
                                $postdata["currentChunk"] = 0;

                                $chapters = $this->getChapters($data["event"][0]->eventID);
                                $chapters[$data["event"][0]->currentChapter]["done"] = true;
                                $this->eventModel->updateChapter(
                                    ["done" => true],
                                    [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data["event"][0]->currentChapter
                                    ]);

                                if ($data["event"][0]->inputMode == InputMode::SCRIPTURE_INPUT) {
                                    // Check if whole book is finished
                                    if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum)) {
                                        $this->eventModel->updateEvent([
                                            "state" => EventStates::TRANSLATED,
                                            "dateTo" => date("Y-m-d H:i:s", time())],
                                            ["eventID" => $data["event"][0]->eventID]);

                                        $event = $this->eventRepo->get($data["event"][0]->eventID);
                                        $this->sendBookCompletedNotif($event);
                                    }
                                } else {
                                    $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                    if (!isset($peerCheck[$data["event"][0]->currentChapter])) {
                                        $peerCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }
                                    $postdata["peerCheck"] = json_encode($peerCheck);
                                }

                                // Check if the member has another chapter to translate
                                // then redirect to preparation page
                                if ($nextChapter > 0) {
                                    $postdata["step"] = EventSteps::PRAY;
                                }

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator/' . $data["event"][0]->eventID . $chapterLink);
                            }
                        }

                        $data["nextChapter"] = $nextChapter;
                        $page = $data["event"][0]->inputMode == InputMode::NORMAL
                            ? "SelfCheck" : "SelfCheckInputMode";
                        $data["next_step"] = $data["event"][0]->inputMode == InputMode::SCRIPTURE_INPUT
                            ? "continue_alt" : EventSteps::PEER_REVIEW;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/' . $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::FINISHED:
                        $data["success"] = __("you_event_finished_success");

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/Finished')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/L1/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/L1/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorContinue($eventID, $chapter)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID, $chapter, true);

        if (!empty($data["event"])) {
            if (!in_array($data["event"][0]->bookProject, ["ulb", "udb"])) {
                Url::redirect("events/translator-" . $data["event"][0]->bookProject . "/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();
                $currentStep = $data["event"][0]->step;

                switch ($currentStep) {
                    case EventSteps::PEER_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $arr["firstvs"] = $tv->firstvs;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                $postdata = [];
                                if ($peerCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $peerCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $chapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    // Only for speech-to-text mode
                                    if ($data["event"][0]->inputMode == InputMode::SPEECH_TO_TEXT) {
                                        // Check if whole book is finished
                                        $chapters = $this->getChapters($data["event"][0]->eventID);

                                        if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $data["event"][0]->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event);
                                        }

                                        // Check if the member has another chapter to translate
                                        // then redirect to preparation page
                                        $nextChapter = 0;
                                        $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));
                                        if (!empty($nextChapterDB))
                                            $nextChapter = $nextChapterDB[0]->chapter;

                                        if ($nextChapter > 0) {
                                            $postdata["step"] = EventSteps::PRAY;
                                        }
                                    } else {
                                        $kwCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata["peerCheck"] = json_encode($peerCheck);
                                    $postdata["kwCheck"] = json_encode($kwCheck);

                                    $chapterLink = $data["event"][0]->inputMode == InputMode::NORMAL
                                        ? '/' . $data["event"][0]->currentChapter : "";

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator/'
                                        . $data["event"][0]->eventID
                                        . $chapterLink);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $page = $data["event"][0]->inputMode == InputMode::NORMAL
                            ? "PeerReview" : "PeerReviewInputMode";
                        $data["next_step"] = $data["event"][0]->inputMode == InputMode::SPEECH_TO_TEXT
                            ? "continue_alt" : EventSteps::KEYWORD_CHECK;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/' . $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::KEYWORD_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                if ($kwCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $kwCheck[$data["event"][0]->currentChapter]["done"] = 2;
                                    $crCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                    $postdata = [
                                        "kwCheck" => json_encode($kwCheck),
                                        "crCheck" => json_encode($crCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $chapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    Url::redirect('events/translator/'
                                        . $data["event"][0]->eventID
                                        . '/' . $data["event"][0]->currentChapter);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::CONTENT_REVIEW;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONTENT_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                                if ($crCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $crCheck[$data["event"][0]->currentChapter]["done"] = 2;
                                    $otherCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                    $postdata = [
                                        "crCheck" => json_encode($crCheck),
                                        "otherCheck" => json_encode($otherCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $chapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    Url::redirect('events/translator/'
                                        . $data["event"][0]->eventID
                                        . '/' . $data["event"][0]->currentChapter);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::FINAL_REVIEW;

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/ContentReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::FINAL_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = [];

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : array();
                                $chunks = Tools::trim($chunks);
                                $chunks = array_filter($chunks, function ($v) {
                                    return !empty($v);
                                });

                                if (sizeof($chunks) == sizeof($data["chunks"])) {
                                    $versesCombined = [];
                                    foreach ($chunks as $key => $chunk) {
                                        $verses = preg_split("/\|(\d+)\|/", $chunk, -1, PREG_SPLIT_NO_EMPTY);

                                        if (sizeof($data["chunks"][$key]) != sizeof($verses)) {
                                            $error[] = __("not_equal_verse_markers");
                                            break;
                                        }

                                        $versesCombined[$key] = array_combine($data["chunks"][$key], $verses);
                                    }

                                    $versesCombined = Tools::trim($versesCombined);

                                    if (!isset($error)) {
                                        foreach ($versesCombined as $key => $chunk) {
                                            $translation[$key][EventMembers::TRANSLATOR]["verses"] = $chunk;

                                            $tID = $translation[$key]["tID"];
                                            unset($translation[$key]["tID"]);

                                            $encoded = json_encode($translation[$key]);
                                            $json_error = json_last_error();

                                            if ($json_error == JSON_ERROR_NONE) {
                                                $trData = array(
                                                    "translatedVerses" => $encoded,
                                                    "translateDone" => true
                                                );
                                                $this->translationModel->updateTranslation(
                                                    $trData,
                                                    array(
                                                        "trID" => $data["event"][0]->trID,
                                                        "tID" => $tID));
                                            } else {
                                                $error[] = __("error_occurred", array($tID));
                                            }
                                        }

                                        $chapters = [];
                                        for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                            $data["chapters"][$i] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        // Check if whole book is finished
                                        if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $data["event"][0]->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event);
                                        }

                                        // Check if the member has another chapter to translate
                                        // then redirect to preparation page
                                        $nextChapter = 0;
                                        $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));
                                        if (!empty($nextChapterDB))
                                            $nextChapter = $nextChapterDB[0]->chapter;

                                        $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                                        $otherCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                        $postdata = [
                                            "step" => $nextChapter > 0 ? EventSteps::PRAY : EventSteps::NONE,
                                            "otherCheck" => json_encode($otherCheck)
                                        ];

                                        $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                        Url::redirect('events/translator/' . $data["event"][0]->eventID);
                                    }
                                } else {
                                    $error[] = __("empty_verses_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/L1/Translator')
                            ->nest('page', 'Events/L1/FinalReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/L1/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/L1/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorNotes($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);
        $data["next_step"] = EventSteps::PRAY;

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tn") {
                if (in_array($data["event"][0]->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $data["event"][0]->bookProject . "/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > -1 ? ($data["event"][0]->currentChapter == 0
                    ? __("front") : $data["event"][0]->currentChapter) : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if (($data["event"][0]->state == EventStates::TRANSLATING
                || $data["event"][0]->state == EventStates::TRANSLATED)) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-tn/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:

                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = [
                                    "step" => !$data["nosource"] ? EventSteps::CONSUME : EventSteps::READ_CHUNK,
                                    "currentChapter" => $data["currentChapter"],
                                    "currentChunk" => $data["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                $nChunks = $this->apiModel->getNotesChunks($sourceTextNotes);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($nChunks)],
                                    [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data['currentChapter']
                                    ]
                                );

                                Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->otherCheck == "";
                        $data["next_step"] = EventSteps::CONSUME . "_tn";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::CONSUME: // Consume chapter
                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                        }

                        if (isset($data["nosource"]) && $data["nosource"] === true) {
                            $this->eventModel->updateTranslator(["step" => EventSteps::READ_CHUNK], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (empty($data["chunks"])) {
                                    $nChunks = $this->apiModel->getNotesChunks($sourceTextNotes);

                                    $this->eventModel->updateChapter(
                                        ["chunks" => json_encode($nChunks)],
                                        [
                                            "eventID" => $data["event"][0]->eventID,
                                            "chapter" => $data['currentChapter']
                                        ]
                                    );
                                }

                                $postdata = [
                                    "step" => EventSteps::READ_CHUNK,
                                ];

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::READ_CHUNK . "_tn";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::READ_CHUNK:
                        $sourceText = $this->getScriptureSourceText($data, true);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data, true);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $this->eventModel->updateTranslator(["step" => EventSteps::BLIND_DRAFT], ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::BLIND_DRAFT;

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/ReadChunk')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::BLIND_DRAFT: // Self-Check Notes
                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data, true);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data, true);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter,
                                    $data["event"][0]->currentChunk);

                                if (!empty($translationData)) {
                                    $verses = json_decode($translationData[0]->translatedVerses, true);
                                    $data["translation"] = $verses[EventMembers::TRANSLATOR]["verses"];
                                }
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $chunk = isset($_POST["draft"]) ? $_POST["draft"] : "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim(strip_tags($chunk)) != "") {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    // If chapter is finished go to SELF_EDIT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $data["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::READ_CHUNK;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("empty_draft_verses_error");
                                }
                            }
                        }

                        if (array_key_exists($data["event"][0]->currentChunk + 1, $data["chunks"])) {
                            $data["next_step"] = EventSteps::READ_CHUNK . "_tn";
                        } else {
                            $data["next_step"] = EventSteps::SELF_CHECK;
                        }

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/BlindDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:
                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);
                        $translation = [];

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;
                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $this->translationModel->updateTranslation(
                                    ["translateDone" => true],
                                    [
                                        "trID" => $data["event"][0]->trID,
                                        "chapter" => $data["event"][0]->currentChapter
                                    ]
                                );

                                $chapters = [];
                                for ($i = 0; $i <= $data["event"][0]->chaptersNum; $i++) {
                                    $data["chapters"][$i] = [];
                                }

                                $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                foreach ($chaptersDB as $chapter) {
                                    $tmp["trID"] = $chapter["trID"];
                                    $tmp["memberID"] = $chapter["memberID"];
                                    $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                    $tmp["done"] = $chapter["done"];

                                    $chapters[$chapter["chapter"]] = $tmp;
                                }

                                $chapters[$data["event"][0]->currentChapter]["done"] = true;
                                $this->eventModel->updateChapter(["done" => true], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter]);

                                // Check if the member has another chapter to translate
                                // then redirect to preparation page
                                $nextChapter = -1;
                                $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));

                                if (!empty($nextChapterDB))
                                    $nextChapter = $nextChapterDB[0]->chapter;

                                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                                if (!array_key_exists($data['currentChapter'], $otherCheck)) {
                                    $otherCheck[$data['currentChapter']] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                }

                                $postdata = [
                                    "step" => EventSteps::NONE,
                                    "currentChapter" => -1,
                                    "currentChunk" => 0,
                                    "otherCheck" => json_encode($otherCheck)
                                ];

                                if ($nextChapter > -1) {
                                    $postdata["step"] = EventSteps::PRAY;
                                    $postdata["currentChapter"] = $nextChapter;
                                }

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-tn/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Notes/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Notes/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorQuestions($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tq") {
                if (in_array($data["event"][0]->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $data["event"][0]->bookProject . "/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if (($data["event"][0]->state == EventStates::TRANSLATING
                || $data["event"][0]->state == EventStates::TRANSLATED)) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-tq/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:

                        // Get questions
                        $sourceTextQuestions = $this->getQuestionsSourceText($data);

                        if ($sourceTextQuestions !== false) {
                            if (!array_key_exists("error", $sourceTextQuestions)) {
                                $data = $sourceTextQuestions;
                            } else {
                                $error[] = $sourceTextQuestions["error"];
                                $data["error"] = $sourceTextQuestions["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tq/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = [
                                    "step" => EventSteps::MULTI_DRAFT,
                                    "currentChapter" => $data["currentChapter"],
                                    "currentChunk" => $data["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                $qChunks = $this->apiModel->getQuestionsChunks($sourceTextQuestions);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($qChunks)],
                                    [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data['currentChapter']
                                    ]
                                );

                                Url::redirect('events/translator-tq/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->otherCheck == "";
                        $data["next_step"] = EventSteps::MULTI_DRAFT;

                        return View::make('Events/Questions/Translator')
                            ->nest('page', 'Events/Questions/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);


                    case EventSteps::MULTI_DRAFT: // Consume/Verbalize/Draft Questions

                        // Get questions
                        $sourceTextQuestions = $this->getQuestionsSourceText($data);

                        if ($sourceTextQuestions !== false) {
                            if (!array_key_exists("error", $sourceTextQuestions)) {
                                $data = $sourceTextQuestions;

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextQuestions["error"];
                                $data["error"] = $sourceTextQuestions["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tq/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkQuestions($chunks, $data["questions"]);
                                if (!$chunks === false) {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-tq/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK;

                        return View::make('Events/Questions/Translator')
                            ->nest('page', 'Events/Questions/MultiDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:

                        // Get notes
                        $sourceTextQuestions = $this->getQuestionsSourceText($data);

                        if ($sourceTextQuestions !== false) {
                            if (!array_key_exists("error", $sourceTextQuestions)) {
                                $data = $sourceTextQuestions;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextQuestions["error"];
                                $data["error"] = $sourceTextQuestions["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tq/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkQuestions($chunks, $data["questions"]);
                                if (!$chunks === false) {
                                    $this->translationModel->updateTranslation(
                                        ["translateDone" => true],
                                        [
                                            "trID" => $data["event"][0]->trID,
                                            "chapter" => $data["event"][0]->currentChapter
                                        ]
                                    );

                                    $chapters = [];
                                    for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["done"] = $chapter["done"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$data["event"][0]->currentChapter]["done"] = true;
                                    $this->eventModel->updateChapter(["done" => true], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter]);

                                    // Check if the member has another chapter to translate
                                    // then redirect to preparation page
                                    $nextChapter = 0;
                                    $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                                    if (!array_key_exists($data['currentChapter'], $otherCheck)) {
                                        $otherCheck[$data['currentChapter']] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => 0,
                                        "currentChunk" => 0,
                                        "otherCheck" => json_encode($otherCheck)
                                    ];

                                    if ($nextChapter > 0) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-tq/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Questions/Translator')
                            ->nest('page', 'Events/Questions/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Questions/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Questions/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorWords($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        $title = "";

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tw") {
                if (in_array($data["event"][0]->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $data["event"][0]->bookProject . "/" . $eventID);
            }

            if (($data["event"][0]->state == EventStates::TRANSLATING
                || $data["event"][0]->state == EventStates::TRANSLATED)) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-tw/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:

                        // Get Words
                        $sourceTextWords = $this->getWordsSourceText($data);

                        if ($sourceTextWords !== false) {
                            if (!array_key_exists("error", $sourceTextWords)) {
                                $data = $sourceTextWords;

                                $title = $data["event"][0]->name
                                    . " " . ($data["event"][0]->currentChapter > 0
                                        ? " [" . $data["group"][0] . "..." . $data["group"][sizeof($data["group"]) - 1] . "]"
                                        : "")
                                    . " - " . $data["event"][0]->tLang
                                    . " - " . __($data["event"][0]->bookProject);
                            } else {
                                $error[] = $sourceTextWords["error"];
                                $data["error"] = $sourceTextWords["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = [
                                    "step" => EventSteps::MULTI_DRAFT,
                                    "currentChapter" => $data["currentChapter"],
                                    "currentChunk" => $data["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                $wChunks = array_keys($sourceTextWords["words"]);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($wChunks)],
                                    [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data['currentChapter']
                                    ]
                                );

                                Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->kwCheck == "";
                        $data["next_step"] = EventSteps::MULTI_DRAFT;

                        return View::make('Events/TWords/Translator')
                            ->nest('page', 'Events/TWords/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);


                    case EventSteps::MULTI_DRAFT: // Consume/Verbalize/Draft/Self-Check Questions

                        // Get notes
                        $sourceTextWords = $this->getWordsSourceText($data);

                        if ($sourceTextWords !== false) {
                            if (!array_key_exists("error", $sourceTextWords)) {
                                $data = $sourceTextWords;

                                $title = $data["event"][0]->name
                                    . " " . ($data["event"][0]->currentChapter > 0
                                        ? " [" . $data["group"][0] . "..." . $data["group"][sizeof($data["group"]) - 1] . "]"
                                        : "")
                                    . " - " . $data["event"][0]->tLang
                                    . " - " . __($data["event"][0]->bookProject);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextWords["error"];
                                $data["error"] = $sourceTextWords["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkWords($chunks, $data["words"]);
                                if (!$chunks === false) {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK;

                        return View::make('Events/TWords/Translator')
                            ->nest('page', 'Events/TWords/MultiDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:

                        // Get notes
                        $sourceTextWords = $this->getWordsSourceText($data);

                        if ($sourceTextWords !== false) {
                            if (!array_key_exists("error", $sourceTextWords)) {
                                $data = $sourceTextWords;

                                $title = $data["event"][0]->name
                                    . " " . ($data["event"][0]->currentChapter > 0
                                        ? " [" . $data["group"][0] . "..." . $data["group"][sizeof($data["group"]) - 1] . "]"
                                        : "")
                                    . " - " . $data["event"][0]->tLang
                                    . " - " . __($data["event"][0]->bookProject);

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextWords["error"];
                                $data["error"] = $sourceTextWords["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkWords($chunks, $data["words"]);
                                if (!$chunks === false) {
                                    $this->translationModel->updateTranslation(
                                        ["translateDone" => true],
                                        [
                                            "trID" => $data["event"][0]->trID,
                                            "chapter" => $data["event"][0]->currentChapter
                                        ]
                                    );

                                    $word_groups = $this->eventModel->getWordGroups([
                                        "eventID" => $data["event"][0]->eventID
                                    ]);

                                    $chapters = [];
                                    foreach ($word_groups as $group) {
                                        $data["chapters"][$group->groupID] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["done"] = $chapter["done"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$data["event"][0]->currentChapter]["done"] = true;
                                    $this->eventModel->updateChapter(["done" => true], [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data["event"][0]->currentChapter]);

                                    // Check if the member has another chapter to translate
                                    // then redirect to preparation page
                                    $nextChapter = 0;
                                    $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                                    if (!array_key_exists($data['currentChapter'], $otherCheck)) {
                                        $otherCheck[$data['currentChapter']] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => 0,
                                        "currentChunk" => 0,
                                        "otherCheck" => json_encode($otherCheck)
                                    ];

                                    if ($nextChapter > 0) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/TWords/Translator')
                            ->nest('page', 'Events/TWords/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/TWords/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/TWords/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorSun($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "sun") {
                if (in_array($data["event"][0]->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $data["event"][0]->bookProject . "/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-sun/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $postdata = [
                                    "step" => EventSteps::CONSUME,
                                    "currentChapter" => $sourceText["currentChapter"],
                                    "currentChunk" => $sourceText["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->kwCheck == "";
                        $data["next_step"] = EventSteps::CONSUME;

                        return View::make('Events/SUN/Translator')
                            ->nest('page', 'Events/SUN/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONSUME:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = [
                                    "step" => EventSteps::CHUNKING
                                ];

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::CHUNKING . "_sun";

                        return View::make('Events/SUN/Translator')
                            ->nest('page', 'Events/SUN/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CHUNKING:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $_POST = Gump::xss_clean($_POST);

                                $chunks = isset($_POST["chunks_array"]) ? $_POST["chunks_array"] : "";
                                $chunks = (array)json_decode($chunks);
                                if ($this->apiModel->testChunks($chunks, $sourceText["totalVerses"])) {
                                    if ($this->eventModel->updateChapter(["chunks" => json_encode($chunks)], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter])) {
                                        $this->eventModel->updateTranslator(["step" => EventSteps::REARRANGE], ["trID" => $data["event"][0]->trID]);
                                        Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                                        exit;
                                    } else {
                                        $error[] = __("error_occurred");
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::REARRANGE;

                        return View::make('Events/SUN/Translator')
                            ->nest('page', 'Events/SUN/Chunking')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::REARRANGE:
                        $sourceText = $this->getScriptureSourceText($data, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel
                                    ->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter, $data["event"][0]->currentChunk);

                                if (!empty($translationData)) {
                                    $verses = json_decode($translationData[0]->translatedVerses, true);
                                    $data["words"] = $verses[EventMembers::TRANSLATOR]["words"];
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            $words = isset($_POST["draft"]) ? $_POST["draft"] : "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim($words) != "") {
                                    $postdata["step"] = EventSteps::SYMBOL_DRAFT;
                                    $postdata["currentChunk"] = 0;

                                    // If chapter is finished go to SYMBOL_DRAFT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::REARRANGE;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("empty_words_error");
                                }
                            }
                        }

                        if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                            $data["next_step"] = "continue_alt";
                        } else {
                            $data["next_step"] = EventSteps::SYMBOL_DRAFT;
                        }

                        return View::make('Events/SUN/Translator')
                            ->nest('page', 'Events/SUN/WordsDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SYMBOL_DRAFT:
                        $sourceText = $this->getScriptureSourceText($data, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel
                                    ->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter, $data["event"][0]->currentChunk);

                                if (!empty($translationData)) {
                                    $verses = json_decode($translationData[0]->translatedVerses, true);
                                    $data["words"] = $verses[EventMembers::TRANSLATOR]["words"];
                                    $data["symbols"] = $verses[EventMembers::TRANSLATOR]["symbols"];
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            $symbols = isset($_POST["symbols"]) ? $_POST["symbols"] : "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim($symbols) != "") {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    // If chapter is finished go to SELF_EDIT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::SYMBOL_DRAFT;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("empty_words_error");
                                }
                            }
                        }

                        if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                            $data["next_step"] = "continue_alt";
                        } else {
                            $data["next_step"] = EventSteps::SELF_CHECK;
                        }

                        return View::make('Events/SUN/Translator')
                            ->nest('page', 'Events/SUN/SymbolsDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $chapters = [];
                                for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                    $data["chapters"][$i] = [];
                                }

                                $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                foreach ($chaptersDB as $chapter) {
                                    $tmp["trID"] = $chapter["trID"];
                                    $tmp["memberID"] = $chapter["memberID"];
                                    $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                    $tmp["done"] = $chapter["done"];

                                    $chapters[$chapter["chapter"]] = $tmp;
                                }

                                $chapters[$data["event"][0]->currentChapter]["done"] = true;
                                $this->eventModel->updateChapter(["done" => true], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter]);

                                // Check if the member has another chapter to translate
                                // then redirect to preparation page
                                $nextChapter = 0;
                                $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));
                                if (!empty($nextChapterDB))
                                    $nextChapter = $nextChapterDB[0]->chapter;

                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                if (!array_key_exists($data["event"][0]->currentChapter, $kwCheck)) {
                                    $kwCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                }

                                $postdata = [
                                    "step" => EventSteps::NONE,
                                    "currentChapter" => 0,
                                    "currentChunk" => 0,
                                    "kwCheck" => json_encode($kwCheck)
                                ];

                                if ($nextChapter > 0) {
                                    $postdata["step"] = EventSteps::PRAY;
                                    $postdata["currentChapter"] = $nextChapter;
                                }

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-sun/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/SUN/Translator')
                            ->nest('page', 'Events/SUN/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/SUN/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/SUN/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorOdbSun($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "sun" && $data["event"][0]->sourceBible != "odb") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . $data["event"][0]->sourceBible
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-odb-sun/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {


                                $keys = array_keys($sourceText["text"]);
                                $chunks = array_map(function ($elm) {
                                    return [$elm];
                                }, $keys);
                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($chunks)],
                                    [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data['currentChapter']
                                    ]
                                );

                                $postdata = [
                                    "step" => EventSteps::CONSUME,
                                    "currentChapter" => $sourceText["currentChapter"],
                                    "currentChunk" => $sourceText["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["event"][0]->justStarted = $data["event"][0]->kwCheck == "";
                        $data["next_step"] = EventSteps::CONSUME . "_odb";

                        return View::make('Events/ODBSUN/Translator')
                            ->nest('page', 'Events/ODBSUN/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONSUME:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = [
                                    "step" => EventSteps::REARRANGE
                                ];

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::REARRANGE;

                        return View::make('Events/ODBSUN/Translator')
                            ->nest('page', 'Events/ODBSUN/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::REARRANGE:
                        $sourceText = $this->getOtherSourceText($data, true);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel
                                    ->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter, $data["event"][0]->currentChunk);

                                if (!empty($translationData)) {
                                    $verses = json_decode($translationData[0]->translatedVerses, true);
                                    $data["words"] = $verses[EventMembers::TRANSLATOR]["words"];
                                }

                                // Skip if section is empty or it is a DATE section
                                $section = key($data["text"]);
                                if (trim($data["text"][$section]) == "" || $section == OdbSections::DATE) {
                                    $translationVerses = [
                                        EventMembers::TRANSLATOR => [
                                            "words" => "",
                                            "symbols" => "",
                                            "bt" => "",
                                            "verses" => [$section => trim($data["text"][$section])]
                                        ],
                                        EventMembers::L2_CHECKER => [
                                            "verses" => array()
                                        ],
                                        EventMembers::L3_CHECKER => [
                                            "verses" => array()
                                        ],
                                    ];

                                    $encoded = json_encode($translationVerses);
                                    $json_error = json_last_error();

                                    if ($json_error == JSON_ERROR_NONE) {
                                        $trData = [
                                            "projectID" => $data["event"][0]->projectID,
                                            "eventID" => $data["event"][0]->eventID,
                                            "trID" => $data["event"][0]->trID,
                                            "targetLang" => $data["event"][0]->targetLang,
                                            "bookProject" => $data["event"][0]->bookProject,
                                            "sort" => $data["event"][0]->sort,
                                            "bookCode" => $data["event"][0]->bookCode,
                                            "chapter" => $data["event"][0]->currentChapter,
                                            "chunk" => $data["event"][0]->currentChunk,
                                            "firstvs" => $sourceText["chunk"][0],
                                            "translatedVerses" => $encoded,
                                            "dateCreate" => date('Y-m-d H:i:s')
                                        ];

                                        if (empty($translationData))
                                            $this->translationModel->createTranslation($trData);

                                        $postdata["step"] = EventSteps::SYMBOL_DRAFT;
                                        $postdata["currentChunk"] = 0;

                                        // If chapter is finished go to SYMBOL_DRAFT, otherwise go to the next chunk
                                        if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                            // Current chunk is finished, go to next chunk
                                            $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                            $postdata["step"] = EventSteps::REARRANGE;
                                        }

                                        $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                        Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                                    }
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            $words = isset($_POST["draft"]) ? $_POST["draft"] : "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim($words) != "") {
                                    $postdata["step"] = EventSteps::SYMBOL_DRAFT;
                                    $postdata["currentChunk"] = 0;

                                    // If chapter is finished go to SYMBOL_DRAFT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::REARRANGE;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("empty_words_error");
                                }
                            }
                        }

                        if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                            $data["next_step"] = "continue_alt";
                        } else {
                            $data["next_step"] = EventSteps::SYMBOL_DRAFT;
                        }

                        return View::make('Events/ODBSUN/Translator')
                            ->nest('page', 'Events/ODBSUN/WordsDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SYMBOL_DRAFT:
                        $sourceText = $this->getOtherSourceText($data, true);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel
                                    ->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter, $data["event"][0]->currentChunk);

                                if (!empty($translationData)) {
                                    $verses = json_decode($translationData[0]->translatedVerses, true);
                                    $data["words"] = $verses[EventMembers::TRANSLATOR]["words"];
                                    $data["symbols"] = $verses[EventMembers::TRANSLATOR]["symbols"];
                                }

                                // Skip if section is empty or it is a DATE section
                                $section = key($data["text"]);
                                if (trim($data["text"][$section]) == "" || $section == OdbSections::DATE) {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    // If chapter is finished go to SELF_EDIT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::SYMBOL_DRAFT;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);
                            $symbols = isset($_POST["symbols"]) ? $_POST["symbols"] : "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim($symbols) != "") {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    // If chapter is finished go to SELF_EDIT, otherwise go to the next chunk
                                    if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $data["event"][0]->currentChunk + 1;
                                        $postdata["step"] = EventSteps::SYMBOL_DRAFT;
                                    }

                                    $upd = $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("empty_words_error");
                                }
                            }
                        }

                        if (array_key_exists($data["event"][0]->currentChunk + 1, $sourceText["chunks"])) {
                            $data["next_step"] = "continue_alt";
                        } else {
                            $data["next_step"] = EventSteps::SELF_CHECK;
                        }

                        return View::make('Events/ODBSUN/Translator')
                            ->nest('page', 'Events/ODBSUN/SymbolsDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $chapters = [];
                                for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                    $data["chapters"][$i] = [];
                                }

                                $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                foreach ($chaptersDB as $chapter) {
                                    $tmp["trID"] = $chapter["trID"];
                                    $tmp["memberID"] = $chapter["memberID"];
                                    $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                    $tmp["done"] = $chapter["done"];

                                    $chapters[$chapter["chapter"]] = $tmp;
                                }

                                $chapters[$data["event"][0]->currentChapter]["done"] = true;
                                $this->eventModel->updateChapter(["done" => true], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter]);

                                // Check if the member has another chapter to translate
                                // then redirect to preparation page
                                $nextChapter = 0;
                                $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));
                                if (!empty($nextChapterDB))
                                    $nextChapter = $nextChapterDB[0]->chapter;

                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                if (!array_key_exists($data["event"][0]->currentChapter, $kwCheck)) {
                                    $kwCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                }

                                $postdata = [
                                    "step" => EventSteps::NONE,
                                    "currentChapter" => 0,
                                    "currentChunk" => 0,
                                    "kwCheck" => json_encode($kwCheck)
                                ];

                                if ($nextChapter > 0) {
                                    $postdata["step"] = EventSteps::PRAY;
                                    $postdata["currentChapter"] = $nextChapter;
                                }

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/ODBSUN/Translator')
                            ->nest('page', 'Events/ODBSUN/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/ODBSUN/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/ODBSUN/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorRadio($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "rad") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . $data["event"][0]->sourceBible
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-rad/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $keys = array_keys($sourceText["text"]);
                                $chunks = array_map(function ($elm) {
                                    return [$elm];
                                }, $keys);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($chunks)],
                                    [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data['currentChapter']
                                    ]
                                );

                                $postdata = [
                                    "step" => EventSteps::CONSUME,
                                    "currentChapter" => $sourceText["currentChapter"],
                                    "currentChunk" => $sourceText["currentChunk"]
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                                Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["event"][0]->justStarted = $data["event"][0]->peerCheck == "";
                        $data["next_step"] = EventSteps::CONSUME . "_odb";

                        return View::make('Events/Radio/Translator')
                            ->nest('page', 'Events/Radio/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONSUME:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-odb-sun/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = [
                                    "step" => EventSteps::MULTI_DRAFT
                                ];

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::MULTI_DRAFT;

                        return View::make('Events/Radio/Translator')
                            ->nest('page', 'Events/Radio/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::MULTI_DRAFT:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter
                                );

                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkRadio($chunks, $data["chunks"]);

                                if (!$chunks === false) {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK;

                        return View::make('Events/Radio/Translator')
                            ->nest('page', 'Events/Radio/MultiDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $this->translationModel->updateTranslation(
                                    ["translateDone" => true],
                                    [
                                        "trID" => $data["event"][0]->trID,
                                        "chapter" => $data["event"][0]->currentChapter
                                    ]
                                );

                                $chapters = [];
                                for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                    $data["chapters"][$i] = [];
                                }

                                $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                foreach ($chaptersDB as $chapter) {
                                    $tmp["trID"] = $chapter["trID"];
                                    $tmp["memberID"] = $chapter["memberID"];
                                    $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                    $tmp["done"] = $chapter["done"];

                                    $chapters[$chapter["chapter"]] = $tmp;
                                }

                                $chapters[$data["event"][0]->currentChapter]["done"] = true;

                                $this->eventModel->updateChapter(["done" => true], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter]);

                                // Check if the member has another chapter to translate
                                // then redirect to preparation page
                                $nextChapter = 0;
                                $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"));
                                if (!empty($nextChapterDB))
                                    $nextChapter = $nextChapterDB[0]->chapter;

                                // For the first checker
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                if (!array_key_exists($data['currentChapter'], $peerCheck)) {
                                    $peerCheck[$data['currentChapter']] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                }

                                $postdata = [
                                    "step" => EventSteps::NONE,
                                    "currentChapter" => 0,
                                    "currentChunk" => 0,
                                    "peerCheck" => json_encode($peerCheck)
                                ];

                                if ($nextChapter > 0) {
                                    $postdata["step"] = EventSteps::PRAY;
                                    $postdata["currentChapter"] = $nextChapter;
                                }

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/translator-rad/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Radio/Translator')
                            ->nest('page', 'Events/Radio/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Radio/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Radio/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorObs($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            $event = $data["event"][0];

            if ($event->bookProject != "obs") {
                if (in_array($event->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $event->bookProject . "/" . $eventID);
            }

            $title = $event->name
                . " " . ($event->currentChapter > 0 ? $event->currentChapter : "")
                . " - " . $event->tLang
                . " - " . __($event->bookProject);

            if (($event->state == EventStates::TRANSLATING
                || $event->state == EventStates::TRANSLATED)) {
                if ($event->step == EventSteps::NONE)
                    Url::redirect("events/information-obs/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($event->step) {
                    case EventSteps::PRAY:
                        if ($event->currentChapter == 0) {
                            $nextChapter = $this->eventModel->getNextChapter($event->eventID, $event->myMemberID);
                            if (!empty($nextChapter)) {
                                $event->currentChapter = $nextChapter[0]->chapter;
                            }
                        }

                        // Get obs
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);

                        if (!$sourceTextObs) {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-obs/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $obsChunks = $this->apiModel->getObsChunks($sourceTextObs);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($obsChunks)],
                                    [
                                        "eventID" => $event->eventID,
                                        "chapter" => $event->currentChapter
                                    ]
                                );

                                $postdata = [
                                    "step" => EventSteps::CONSUME,
                                    "currentChapter" => $event->currentChapter,
                                    "currentChunk" => 0
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);

                                Url::redirect('events/translator-obs/' . $event->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $event->justStarted = $event->otherCheck == "";
                        $data["next_step"] = EventSteps::CONSUME;

                        return View::make('Events/Obs/Translator')
                            ->nest('page', 'Events/Obs/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::CONSUME:
                        // Get obs
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);

                        if (!$sourceTextObs) {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-obs/' . $event->eventID);
                        }

                        $data["obs"] = $sourceTextObs->chunks;

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $postdata = ["step" => EventSteps::BLIND_DRAFT];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);

                                Url::redirect('events/translator-obs/' . $event->eventID);
                            }
                        }

                        $data["next_step"] = EventSteps::BLIND_DRAFT;

                        return View::make('Events/Obs/Translator')
                            ->nest('page', 'Events/Obs/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::BLIND_DRAFT:

                        // Get obs chapter
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);

                        $data["chunks"] = $this->apiModel->getObsChunks($sourceTextObs);

                        if ($sourceTextObs) {
                            $data["obs"] = $sourceTextObs->chunks->get($event->currentChunk);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter,
                                $event->currentChunk
                            );

                            if (!empty($translationData)) {
                                $verses = json_decode($translationData[0]->translatedVerses, true);
                                $data["translation"] = $verses[EventMembers::TRANSLATOR]["verses"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-obs/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $title = $_POST["draft"] ?? "";

                            if (isset($_POST["confirm_step"])) {
                                if (trim(strip_tags($title)) != "") {
                                    $postdata["step"] = EventSteps::SELF_CHECK;

                                    // If chapter is finished go to SELF_EDIT, otherwise go to the next chunk
                                    if (array_key_exists($event->currentChunk + 1, $data["chunks"])) {
                                        // Current chunk is finished, go to next chunk
                                        $postdata["currentChunk"] = $event->currentChunk + 1;
                                        $postdata["step"] = EventSteps::BLIND_DRAFT;
                                    }

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/translator-obs/' . $event->eventID);
                                } else {
                                    $error[] = __("empty_draft_verses_error");
                                }
                            }
                        }

                        if (array_key_exists($event->currentChunk + 1, $data["chunks"])) {
                            $data["next_step"] = EventSteps::BLIND_DRAFT;
                        } else {
                            $data["next_step"] = EventSteps::SELF_CHECK;
                        }

                        return View::make('Events/Obs/Translator')
                            ->nest('page', 'Events/Obs/BlindDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:

                        // Get obs chapter
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);

                        $data["chunks"] = $this->apiModel->getObsChunks($sourceTextObs);

                        if ($sourceTextObs) {
                            $data["obs"] = $sourceTextObs->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-obs/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["obs"]);
                                if (!$chunks === false) {
                                    $this->translationModel->updateTranslation(
                                        ["translateDone" => true],
                                        [
                                            "trID" => $event->trID,
                                            "chapter" => $event->currentChapter
                                        ]
                                    );

                                    $chapters = [];
                                    for ($i = 1; $i <= $event->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($event->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["done"] = $chapter["done"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$event->currentChapter]["done"] = true;
                                    $this->eventModel->updateChapter(
                                        ["done" => true],
                                        ["eventID" => $event->eventID, "chapter" => $event->currentChapter]
                                    );

                                    // Check if the member has another chapter to translate
                                    // then redirect to preparation page
                                    $nextChapter = 0;
                                    $nextChapterDB = $this->eventModel->getNextChapter($event->eventID, Session::get("memberID"));

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $otherCheck = (array)json_decode($event->otherCheck, true);
                                    if (!array_key_exists($event->currentChapter, $otherCheck)) {
                                        $otherCheck[$event->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => 0,
                                        "currentChunk" => 0,
                                        "otherCheck" => json_encode($otherCheck)
                                    ];

                                    if ($nextChapter > 0) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/translator-obs/' . $event->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Obs/Translator')
                            ->nest('page', 'Events/Obs/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Obs/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Obs/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorBc($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            $event = $data["event"][0];

            if ($event->bookProject != "bc") {
                if (in_array($event->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $event->bookProject . "/" . $eventID);
            }

            $title = $event->name
                . " " . ($event->currentChapter > 0 ? $event->currentChapter : "")
                . " - " . $event->tLang
                . " - " . __($event->bookProject);

            if (($event->state == EventStates::TRANSLATING
                || $event->state == EventStates::TRANSLATED)) {
                if ($event->step == EventSteps::NONE)
                    Url::redirect("events/information-bc/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($event->step) {
                    case EventSteps::PRAY:

                        if ($event->currentChapter == -1) {
                            $nextChapter = $this->eventModel->getNextChapter($event->eventID, $event->myMemberID);
                            if (!empty($nextChapter)) {
                                $event->currentChapter = $nextChapter[0]->chapter;
                            }
                        }

                        // Get bc
                        $sourceTextBc = $this->resourcesRepo->getBcSource(
                            $event->resLangID,
                            $event->bookCode,
                            $event->sort,
                            $event->currentChapter
                        );

                        if (!$sourceTextBc) {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-bc/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $bcChunks = $this->apiModel->getResourceChunks($sourceTextBc);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($bcChunks)],
                                    [
                                        "eventID" => $event->eventID,
                                        "chapter" => $event->currentChapter
                                    ]
                                );

                                $postdata = [
                                    "step" => EventSteps::MULTI_DRAFT,
                                    "currentChapter" => $event->currentChapter,
                                    "currentChunk" => 0
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);

                                Url::redirect('events/translator-bc/' . $event->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $event->justStarted = $event->otherCheck == "";
                        $data["next_step"] = EventSteps::MULTI_DRAFT;

                        return View::make('Events/Bc/Translator')
                            ->nest('page', 'Events/Bc/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::MULTI_DRAFT:
                        // Get Bc
                        $sourceTextBc = $this->resourcesRepo->getBcSource(
                            $event->resLangID,
                            $event->bookCode,
                            $event->sort,
                            $event->currentChapter
                        );

                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceTextBc);

                        if ($sourceTextBc) {
                            $data["bc"] = $sourceTextBc->chunks;

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-bc/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["bc"]);

                                if (!$chunks === false) {
                                    $postdata["step"] = EventSteps::SELF_CHECK;
                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/translator-bc/' . $event->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK;

                        return View::make('Events/Bc/Translator')
                            ->nest('page', 'Events/Bc/MultiDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK:
                        // Get obs chapter
                        $sourceTextBc = $this->resourcesRepo->getBcSource(
                            $event->resLangID,
                            $event->bookCode,
                            $event->sort,
                            $event->currentChapter
                        );

                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceTextBc);

                        if ($sourceTextBc) {
                            $data["bc"] = $sourceTextBc->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-bc/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = $_POST["confirm_step"] ?? false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["bc"]);
                                if (!$chunks === false) {
                                    $this->translationModel->updateTranslation(
                                        ["translateDone" => true],
                                        [
                                            "trID" => $event->trID,
                                            "chapter" => $event->currentChapter
                                        ]
                                    );

                                    $chapters = [];
                                    for ($i = 0; $i <= $event->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($event->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["done"] = $chapter["done"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$event->currentChapter]["done"] = true;
                                    $this->eventModel->updateChapter(
                                        ["done" => true],
                                        ["eventID" => $event->eventID, "chapter" => $event->currentChapter]
                                    );

                                    // Check if the member has another chapter to translate
                                    // then redirect to preparation page
                                    $nextChapter = -1;
                                    $nextChapterDB = $this->eventModel->getNextChapter($event->eventID, Session::get("memberID"));

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $otherCheck = (array)json_decode($event->otherCheck, true);
                                    if (!array_key_exists($event->currentChapter, $otherCheck)) {
                                        $otherCheck[$event->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => -1,
                                        "currentChunk" => 0,
                                        "otherCheck" => json_encode($otherCheck)
                                    ];

                                    if ($nextChapter > -1) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/translator-bc/' . $event->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Bc/Translator')
                            ->nest('page', 'Events/Bc/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Bc/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Bc/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function translatorBca($eventID)
    {
        $data["menu"] = 1;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEvents(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            $event = $data["event"][0];
            $eventObj = $this->eventRepo->get($event->eventID);

            if ($event->bookProject != "bca") {
                if (in_array($event->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/translator/" . $eventID);
                else
                    Url::redirect("events/translator-" . $event->bookProject . "/" . $eventID);
            }

            $title = $event->name
                . " " . ($event->currentChapter > 0 ? $event->currentChapter : "")
                . " - " . $event->tLang
                . " - " . __($event->bookProject);

            if (($event->state == EventStates::TRANSLATING
                || $event->state == EventStates::TRANSLATED)) {
                if ($event->step == EventSteps::NONE)
                    Url::redirect("events/information-bca/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($event->step) {
                    case EventSteps::PRAY:
                        if ($event->currentChapter == 0) {
                            $nextChapter = $this->eventModel->getNextChapter($event->eventID, $event->myMemberID);
                            if (!empty($nextChapter)) {
                                $event->currentChapter = $nextChapter[0]->chapter;
                            }
                        }

                        // Get Articles
                        $data["word"] = $eventObj->words->filter(function($word) use ($event) {
                            return $word->wordID == $event->currentChapter;
                        })->first()->word;

                        $sourceArticle = $this->resourcesRepo->getBcArticlesSource(
                            $event->resLangID,
                            $data["word"]
                        );

                        if (!$sourceArticle) {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-bca/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $bcaChunks = $this->apiModel->getResourceChunks($sourceArticle);

                                $this->eventModel->updateChapter(
                                    ["chunks" => json_encode($bcaChunks)],
                                    [
                                        "eventID" => $event->eventID,
                                        "chapter" => $event->currentChapter
                                    ]
                                );

                                $postdata = [
                                    "step" => EventSteps::MULTI_DRAFT,
                                    "currentChapter" => $event->currentChapter,
                                    "currentChunk" => 0
                                ];
                                $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);

                                Url::redirect('events/translator-bca/' . $event->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $event->justStarted = $event->otherCheck == "";
                        $data["next_step"] = EventSteps::MULTI_DRAFT;

                        return View::make('Events/Bca/Translator')
                            ->nest('page', 'Events/Bca/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::MULTI_DRAFT:
                        // Get Articles
                        $data["word"] = $eventObj->words->filter(function($word) use ($event) {
                            return $word->wordID == $event->currentChapter;
                        })->first()->word;

                        $sourceArticle = $this->resourcesRepo->getBcArticlesSource(
                            $event->resLangID,
                            $data["word"]
                        );

                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceArticle);

                        if ($sourceArticle) {
                            $data["bca"] = $sourceArticle->chunks;

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-bca/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["bca"]);

                                if (!$chunks === false) {
                                    $postdata["step"] = EventSteps::SELF_CHECK;
                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/translator-bca/' . $event->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK;

                        return View::make('Events/Bca/Translator')
                            ->nest('page', 'Events/Bca/MultiDraft')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::SELF_CHECK:
                        // Get Articles
                        $data["word"] = $eventObj->words->filter(function($word) use ($event) {
                            return $word->wordID == $event->currentChapter;
                        })->first()->word;

                        $sourceArticle = $this->resourcesRepo->getBcArticlesSource(
                            $event->resLangID,
                            $data["word"]
                        );

                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceArticle);

                        if ($sourceArticle) {
                            $data["bca"] = $sourceArticle->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::NONE], ["trID" => $event->trID]);
                            Url::redirect('events/translator-bca/' . $event->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = $_POST["confirm_step"] ?? false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["bca"]);
                                if (!$chunks === false) {
                                    $this->translationModel->updateTranslation(
                                        ["translateDone" => true],
                                        [
                                            "trID" => $event->trID,
                                            "chapter" => $event->currentChapter
                                        ]
                                    );

                                    $chapters = [];
                                    for ($i = 0; $i <= $eventObj->words->count(); $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($event->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["done"] = $chapter["done"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$event->currentChapter]["done"] = true;
                                    $this->eventModel->updateChapter(
                                        ["done" => true],
                                        ["eventID" => $event->eventID, "chapter" => $event->currentChapter]
                                    );

                                    // Check if the member has another chapter to translate
                                    // then redirect to preparation page
                                    $nextChapter = 0;
                                    $nextChapterDB = $this->eventModel->getNextChapter($event->eventID, Session::get("memberID"));

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $otherCheck = (array)json_decode($event->otherCheck, true);
                                    if (!array_key_exists($event->currentChapter, $otherCheck)) {
                                        $otherCheck[$event->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => 0,
                                        "currentChunk" => 0,
                                        "otherCheck" => json_encode($otherCheck)
                                    ];

                                    if ($nextChapter > 0) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/translator-bca/' . $event->eventID);
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Bca/Translator')
                            ->nest('page', 'Events/Bca/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Bca/Translator')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Bca/Translator')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checker($eventID, $memberID, $chapter) {
        $response = ["success" => false, "errors" => ""];

        $data["event"] = $this->eventModel->getMemberEventsForChecker(
            Session::get("memberID"),
            $eventID,
            $memberID,
            $chapter
        );

        if (!empty($data["event"])) {
            if ($data["event"][0]->step != EventSteps::FINISHED) {
                if (in_array(
                    $data["event"][0]->step,
                    [
                        EventSteps::PEER_REVIEW,
                        EventSteps::KEYWORD_CHECK,
                        EventSteps::CONTENT_REVIEW
                    ])
                ) {
                    $data["turn"] = EventUtil::makeTurnCredentials();

                    $data["comments"] = EventUtil::getComments($data["event"][0]->eventID, $data["event"][0]->currentChapter);
                    $sourceText = $this->getScriptureSourceText($data);

                    if (!empty($sourceText) && !array_key_exists("error", $sourceText)) {
                        $translationData = $this->translationModel->getEventTranslation($data["event"][0]->trID, $data["event"][0]->currentChapter);
                        $translation = array();

                        foreach ($translationData as $tv) {
                            $arr = json_decode($tv->translatedVerses, true);
                            $arr["tID"] = $tv->tID;
                            $arr["firstvs"] = $tv->firstvs;
                            $translation[] = $arr;
                        }

                        $data = $sourceText;
                        $data["translation"] = $translation;
                    } else {
                        $error[] = $sourceText["error"];
                    }

                    if (isset($_POST) && !empty($_POST)) {
                        $_POST = Gump::xss_clean($_POST);

                        if (isset($_POST["confirm_step"])) {
                            if ($data["event"][0]->step == EventSteps::PEER_REVIEW) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                $postdata["peerCheck"] = json_encode($peerCheck);
                            } elseif ($data["event"][0]->step == EventSteps::KEYWORD_CHECK) {
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                $kwCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                $postdata["kwCheck"] = json_encode($kwCheck);
                            } else {
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                $crCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                $postdata["crCheck"] = json_encode($crCheck);
                            }

                            $this->eventModel->updateTranslator($postdata, array("trID" => $data["event"][0]->trID));

                            $response["success"] = true;
                            echo json_encode($response);
                            exit;
                        }
                    }
                } else {
                    $error[] = __("checker_translator_not_ready_error");
                }
            } else {
                $data["success"] = __("translator_event_finished_success");
                $data["error"] = "";
            }

            $title = $data["event"][0]->bookName . " - " . $data["event"][0]->tLang . " - " . __($data["event"][0]->bookProject);
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        $data["menu"] = 1;
        $data["isCheckerPage"] = true;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;

        $page = null;
        if (!isset($error)) {
            switch ($data["event"][0]->step) {
                case EventSteps::PEER_REVIEW:
                    $page = $data["event"][0]->inputMode == InputMode::SPEECH_TO_TEXT
                        ? "Events/L1/CheckerPeerReviewInputMode" : "Events/L1/CheckerPeerReview";
                    break;

                case EventSteps::KEYWORD_CHECK:
                    $page = "Events/L1/CheckerKeywordCheck";
                    break;

                case EventSteps::CONTENT_REVIEW:
                    $page = "Events/L1/CheckerContentReview";
                    break;
            }
        }

        $data["next_step"] = "continue_alt";

        $view = View::make('Events/L1/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);

        if ($page != null) $view->nest('page', $page);

        return $view;
    }

    public function checkerNotes($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["isPeerPage"] = false;
        $data["event"] = $this->eventModel->getMemberEventsForNotes(
            Session::get("memberID"), $eventID, $memberID, $chapter);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tn") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > -1 ? ($data["event"][0]->currentChapter == 0
                    ? __("front") : $data["event"][0]->currentChapter) : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }
                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                $currentStep = $data["event"][0]->step;

                switch ($currentStep) {
                    case EventSteps::PRAY:
                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 6;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $otherCheck)) {
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = !$data["nosource"] ? 1 : 3;
                                }

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $data["event"][0]->trID]);

                                Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["event"][0]->justStarted = true;
                        $data["next_step"] = EventSteps::CONSUME . "_tn";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::CONSUME: // Consume chapter
                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;

                                if (isset($data["nosource"]) && $data["nosource"]) {
                                    // 3 for SELF-CHECK step
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                                    $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                        "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                                }
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            // 6 for the chapter finished
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 6;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                // 2 for HIGHLIGHT step
                                $otherCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["next_step"] = EventSteps::HIGHLIGHT . "_tn";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::HIGHLIGHT: // Highlight chapter
                        // Get scripture text
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;

                                if (isset($data["nosource"]) && $data["nosource"]) {
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                                    $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                        "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                                }
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 6;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                // 3 for SELF_CHECK step
                                $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["next_step"] = EventSteps::SELF_CHECK . "_tn_chk";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/Highlight')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::SELF_CHECK: // Criteria Check Notes
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes

                        $sourceTextNotes = $this->getNotesSourceText($data);
                        $translation = array();

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 6;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                // Update CHECKER if it's empty
                                foreach ($translation as $tr) {
                                    if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                        $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                        $tID = $tr["tID"];
                                        unset($tr["tID"]);
                                        $this->translationModel->updateTranslation(
                                            ["translatedVerses" => json_encode($tr)],
                                            ["tID" => $tID]
                                        );
                                    }
                                }

                                $postdata = [];

                                // 4 for KEYWORD_CHECK step
                                $otherCheck[$data["event"][0]->currentChapter]["done"] = 4;
                                $postdata["otherCheck"] = json_encode($otherCheck);

                                if (isset($data["nosource"]) && $data["nosource"]) {
                                    // 5 for PEER_REVIEW step
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 5;

                                    $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                    $peerCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];

                                    $postdata["otherCheck"] = json_encode($otherCheck);
                                    $postdata["peerCheck"] = json_encode($peerCheck);
                                }

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["next_step"] = EventSteps::KEYWORD_CHECK . "_tn";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/SelfCheckChecker')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::KEYWORD_CHECK: // Highlight Check Notes
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);
                        $translation = array();

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 6;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                // 5 for PEER_REVIEW step
                                $otherCheck[$data["event"][0]->currentChapter]["done"] = 5;

                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $peerCheck[$data["event"][0]->currentChapter] = [
                                    "memberID" => 0,
                                    "done" => 0
                                ];

                                $postdata = [];
                                $postdata["otherCheck"] = json_encode($otherCheck);
                                $postdata["peerCheck"] = json_encode($peerCheck);

                                $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["next_step"] = EventSteps::PEER_REVIEW . "_tn";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', 'Events/Notes/HighlightChecker')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::PEER_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText) && !array_key_exists("error", $sourceText))
                            $data = $sourceText;

                        // Get notes
                        $sourceTextNotes = $this->getNotesSourceText($data);

                        if ($sourceTextNotes !== false) {
                            if (!array_key_exists("error", $sourceTextNotes)) {
                                $data = $sourceTextNotes;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);

                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextNotes["error"];
                                $data["error"] = $sourceTextNotes["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 6;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tn/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                                if ($data["event"][0]->peer == 1) {
                                    if (isset($peerCheck[$data['currentChapter']]) &&
                                        $peerCheck[$data['currentChapter']]["done"]) {
                                        // 6 for chapter finished
                                        $otherCheck[$data['currentChapter']]["done"] = 6;

                                        // Cleanup chapter notifications for this step
                                        $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                            ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                                return $item->eventID == $eventID
                                                    && $item->currentChapter == $chapter
                                                    && $item->step == $currentStep;
                                            });

                                        $myNotifications->each(function($item) {
                                            $item->delete();
                                        });

                                        $this->eventModel->updateTranslator(
                                            ["otherCheck" => json_encode($otherCheck)],
                                            ["trID" => $data["event"][0]->trID]);

                                        $chapters = [];
                                        for ($i = 0; $i <= $data["event"][0]->chaptersNum; $i++) {
                                            $data["chapters"][$i] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];
                                            $tmp["checked"] = $chapter["checked"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        $chapters[$data["event"][0]->currentChapter]["checked"] = true;
                                        $this->eventModel->updateChapter(["checked" => true], [
                                            "eventID" => $data["event"][0]->eventID,
                                            "chapter" => $data["event"][0]->currentChapter]);

                                        // Check if whole scripture is finished
                                        if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum + 1, true)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $data["event"][0]->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event, 2);
                                        }

                                        Url::redirect('events');
                                    } else {
                                        $error[] = __("checker_not_ready_error");
                                    }
                                } else {
                                    $peerCheck[$data['currentChapter']]["done"] = 1;
                                    $this->eventModel->updateTranslator(
                                        ["peerCheck" => json_encode($peerCheck)],
                                        ["trID" => $data["event"][0]->trID]);

                                    $response["success"] = true;
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        if ($data["event"][0]->peer == 1)
                            $page = "Events/Notes/PeerReview";
                        else {
                            $page = "Events/Notes/CheckerPeerReview";
                            $data["isPeerPage"] = true;
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Notes/Translator')
                            ->nest('page', $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/Notes/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * View for Keyword-Check and Peer-Review in Questions event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerQuestions($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["isPeerPage"] = false;
        $data["event"] = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"), $eventID, $memberID, $chapter);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tq") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }
                $data["isCheckerPage"] = true;
                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                $currentStep = $data["event"][0]->step;

                switch ($data["event"][0]->step) {
                    case EventSteps::PRAY:
                        // Get questions
                        $sourceTextQuestions = $this->getQuestionsSourceText($data);

                        if ($sourceTextQuestions !== false) {
                            if (!array_key_exists("error", $sourceTextQuestions)) {
                                $data = $sourceTextQuestions;
                            } else {
                                $error[] = $sourceTextQuestions["error"];
                                $data["error"] = $sourceTextQuestions["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tq/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $otherCheck)) {
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                }

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $data["event"][0]->trID]);

                                Url::redirect('events/checker-tq/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["event"][0]->justStarted = true;
                        $data["next_step"] = EventSteps::KEYWORD_CHECK;

                        return View::make('Events/Questions/Translator')
                            ->nest('page', 'Events/Questions/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::KEYWORD_CHECK:

                        // Get questions
                        $sourceTextQuestions = $this->getQuestionsSourceText($data);
                        if ($sourceTextQuestions !== false) {
                            if (!array_key_exists("error", $sourceTextQuestions)) {
                                $data = $sourceTextQuestions;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextQuestions["error"];
                                $data["error"] = $sourceTextQuestions["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tq/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];

                                $empty = array_filter($chunks, function ($elm) {
                                    return empty(Tools::trim(strip_tags($elm)));
                                });

                                if (empty($empty)) {
                                    // Update Checker if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                            $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }

                                if (!isset($error)) {
                                    // 2 for PEER_REVIEW step
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                    $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                    if (!array_key_exists($data['currentChapter'], $peerCheck)) {
                                        $peerCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "otherCheck" => json_encode($otherCheck),
                                        "peerCheck" => json_encode($peerCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/checker-tq/' . $data["event"][0]->eventID .
                                        "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::PEER_REVIEW . "_tq";

                        return View::make('Events/Questions/Translator')
                            ->nest('page', 'Events/Questions/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::PEER_REVIEW:
                        // Get questions
                        $sourceTextQuestions = $this->getQuestionsSourceText($data);

                        if ($sourceTextQuestions !== false) {
                            if (!array_key_exists("error", $sourceTextQuestions)) {
                                $data = $sourceTextQuestions;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);

                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextQuestions["error"];
                                $data["error"] = $sourceTextQuestions["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tq/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                                if ($data["event"][0]->peer == 1) {
                                    if (isset($peerCheck[$data['currentChapter']]) &&
                                        $peerCheck[$data['currentChapter']]["done"]) {
                                        // 3 for chapter finished
                                        $otherCheck[$data['currentChapter']]["done"] = 3;

                                        // Cleanup chapter notifications for this step
                                        $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                            ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                                return $item->eventID == $eventID
                                                    && $item->currentChapter == $chapter
                                                    && $item->step == $currentStep;
                                            });

                                        $myNotifications->each(function($item) {
                                            $item->delete();
                                        });

                                        $this->eventModel->updateTranslator(
                                            ["otherCheck" => json_encode($otherCheck)],
                                            ["trID" => $data["event"][0]->trID]);

                                        $chapters = [];
                                        for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                            $data["chapters"][$i] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];
                                            $tmp["checked"] = $chapter["checked"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        $chapters[$data["event"][0]->currentChapter]["checked"] = true;
                                        $this->eventModel->updateChapter(["checked" => true], [
                                            "eventID" => $data["event"][0]->eventID,
                                            "chapter" => $data["event"][0]->currentChapter]);

                                        // Check if whole scripture is finished
                                        if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum, true)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $data["event"][0]->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event, 2);
                                        }

                                        Url::redirect('events');
                                    } else {
                                        $error[] = __("checker_not_ready_error");
                                    }
                                } else {
                                    $peerCheck[$data['currentChapter']]["done"] = 1;
                                    $this->eventModel->updateTranslator(
                                        ["peerCheck" => json_encode($peerCheck)],
                                        ["trID" => $data["event"][0]->trID]);

                                    $response["success"] = true;
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        if ($data["event"][0]->peer == 1)
                            $page = "Events/Questions/PeerReview";
                        else {
                            $page = "Events/Questions/CheckerPeerReview";
                            $data["isPeerPage"] = true;
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Questions/Translator')
                            ->nest('page', $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/Questions/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * View for Keyword-Check and Peer-Review in Words event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerWords($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["isPeerPage"] = false;
        $data["event"] = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"), $eventID, $memberID, $chapter);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tw") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name . " - " . $data["event"][0]->tLang . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }
                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                $currentStep = $data["event"][0]->step;

                switch ($currentStep) {
                    case EventSteps::PRAY:
                        // Get questions
                        $sourceTextWords = $this->getWordsSourceText($data);

                        if ($sourceTextWords !== false) {
                            if (!array_key_exists("error", $sourceTextWords)) {
                                $data = $sourceTextWords;
                            } else {
                                $error[] = $sourceTextWords["error"];
                                $data["error"] = $sourceTextWords["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tw/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $otherCheck)) {
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                }

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $data["event"][0]->trID]);

                                Url::redirect('events/checker-tw/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["event"][0]->justStarted = true;
                        $data["next_step"] = EventSteps::KEYWORD_CHECK;

                        return View::make('Events/TWords/Translator')
                            ->nest('page', 'Events/TWords/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::KEYWORD_CHECK:

                        // Get notes
                        $sourceTextWords = $this->getWordsSourceText($data);
                        if ($sourceTextWords !== false) {
                            if (!array_key_exists("error", $sourceTextWords)) {
                                $data = $sourceTextWords;

                                $title = $data["event"][0]->name
                                    . " " . ($data["event"][0]->currentChapter > 0
                                        ? " [" . $data["group"][0] . "..." . $data["group"][sizeof($data["group"]) - 1] . "]"
                                        : "")
                                    . " - " . $data["event"][0]->tLang
                                    . " - " . __($data["event"][0]->bookProject);

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextWords["error"];
                                $data["error"] = $sourceTextWords["error"];
                            }
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/checker-tw/' . $data["event"][0]->eventID .
                                "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];

                                $empty = array_filter($chunks, function ($elm) {
                                    return empty(Tools::trim(strip_tags($elm)));
                                });

                                if (empty($empty)) {
                                    // Update Checker if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                            $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }

                                if (!isset($error)) {
                                    // 2 for PEER_REVIEW step
                                    $otherCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                    $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                    if (!array_key_exists($data['currentChapter'], $peerCheck)) {
                                        $peerCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "otherCheck" => json_encode($otherCheck),
                                        "peerCheck" => json_encode($peerCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/checker-tw/' . $data["event"][0]->eventID .
                                        "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::PEER_REVIEW . "_tw";

                        return View::make('Events/TWords/Translator')
                            ->nest('page', 'Events/TWords/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::PEER_REVIEW:

                        // Get notes
                        $sourceTextWords = $this->getWordsSourceText($data);
                        if ($sourceTextWords !== false) {
                            if (!array_key_exists("error", $sourceTextWords)) {
                                $data = $sourceTextWords;

                                $title = $data["event"][0]->name
                                    . " " . ($data["event"][0]->currentChapter > 0
                                        ? " [" . $data["group"][0] . "..." . $data["group"][sizeof($data["group"]) - 1] . "]"
                                        : "")
                                    . " - " . $data["event"][0]->tLang
                                    . " - " . __($data["event"][0]->bookProject);

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslation(
                                    $data["event"][0]->trID,
                                    $data["event"][0]->currentChapter);
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceTextWords["error"];
                                $data["error"] = $sourceTextWords["error"];
                            }
                        } else {
                            $this->eventModel->updateTranslator(["step" => EventSteps::FINISHED], ["trID" => $data["event"][0]->trID]);
                            Url::redirect('events/translator-tw/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                            if ($confirm_step) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                                if ($data["event"][0]->peer == 1) {
                                    if (isset($peerCheck[$data['currentChapter']]) &&
                                        $peerCheck[$data['currentChapter']]["done"]) {
                                        // 3 for chapter finished
                                        $otherCheck[$data['currentChapter']]["done"] = 3;

                                        // Cleanup chapter notifications for this step
                                        $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                            ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                                return $item->eventID == $eventID
                                                    && $item->currentChapter == $chapter
                                                    && $item->step == $currentStep;
                                            });

                                        $myNotifications->each(function($item) {
                                            $item->delete();
                                        });

                                        $this->eventModel->updateTranslator(
                                            ["otherCheck" => json_encode($otherCheck)],
                                            ["trID" => $data["event"][0]->trID]);

                                        $word_groups = $this->eventModel->getWordGroups([
                                            "eventID" => $data["event"][0]->eventID
                                        ]);

                                        $chapters = [];
                                        foreach ($word_groups as $group) {
                                            $data["chapters"][$group->groupID] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];
                                            $tmp["checked"] = $chapter["checked"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        $chapters[$data["event"][0]->currentChapter]["checked"] = true;
                                        $this->eventModel->updateChapter(["checked" => true], [
                                            "eventID" => $data["event"][0]->eventID,
                                            "chapter" => $data["event"][0]->currentChapter]);

                                        Url::redirect('events');
                                    } else {
                                        $error[] = __("checker_not_ready_error");
                                    }
                                } else {
                                    $peerCheck[$data['currentChapter']]["done"] = 1;
                                    $this->eventModel->updateTranslator(
                                        ["peerCheck" => json_encode($peerCheck)],
                                        ["trID" => $data["event"][0]->trID]);

                                    $response["success"] = true;
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        if ($data["event"][0]->peer == 1)
                            $page = "Events/TWords/PeerReview";
                        else {
                            $page = "Events/TWords/CheckerPeerReview";
                            $data["isPeerPage"] = true;
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/TWords/Translator')
                            ->nest('page', $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/TWords/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * View for Theo check and V-b-v check in SUN event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerSun($eventID, $memberID, $chapter)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEventsForSun(
            Session::get("memberID"), $eventID, $memberID, $chapter);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "sun") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                switch ($data["event"][0]->step) {
                    case EventSteps::THEO_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (!isset($error)) {
                                    $keywords = $this->translationModel->getKeywords([
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data["event"][0]->currentChapter
                                    ]);

                                    if (!empty($keywords)) {
                                        $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                        if (array_key_exists($data["event"][0]->currentChapter, $kwCheck)) {
                                            $kwCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                        }

                                        $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                        $crCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];

                                        $postdata = [
                                            "kwCheck" => json_encode($kwCheck),
                                            "crCheck" => json_encode($crCheck)
                                        ];

                                        $this->eventModel->updateTranslator($postdata, [
                                            "trID" => $data["event"][0]->trID
                                        ]);
                                        Url::redirect('events/');
                                    } else {
                                        $error[] = __("keywords_empty_error");
                                    }
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/SUN/Checker')
                            ->nest('page', 'Events/SUN/TheoCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONTENT_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                if (array_key_exists($data["event"][0]->currentChapter, $crCheck)) {
                                    $crCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                }

                                $postdata = [
                                    "crCheck" => json_encode($crCheck),
                                ];

                                $this->eventModel->updateTranslator($postdata, [
                                    "trID" => $data["event"][0]->trID
                                ]);
                                Url::redirect('events/checker-sun/' . $data["event"][0]->eventID .
                                    "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                            }
                        }

                        $data["next_step"] = EventSteps::FINAL_REVIEW;

                        return View::make('Events/SUN/Checker')
                            ->nest('page', 'Events/SUN/ContentReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::FINAL_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = Tools::trim($chunks);
                                $chunks = array_filter($chunks, function ($v) {
                                    return !empty($v);
                                });

                                if (sizeof($chunks) == sizeof($data["chunks"])) {
                                    $versesCombined = [];
                                    foreach ($chunks as $key => $chunk) {
                                        $verses = preg_split("/\|(\d+)\|/", $chunk, -1, PREG_SPLIT_NO_EMPTY);

                                        if (sizeof($data["chunks"][$key]) != sizeof($verses)) {
                                            $error[] = __("not_equal_verse_markers");
                                            break;
                                        }

                                        $versesCombined[$key] = array_combine($data["chunks"][$key], $verses);
                                    }

                                    $versesCombined = Tools::trim($versesCombined);

                                    if (!isset($error)) {
                                        foreach ($versesCombined as $key => $chunk) {
                                            $translation[$key][EventMembers::TRANSLATOR]["verses"] = $chunk;

                                            $tID = $translation[$key]["tID"];
                                            unset($translation[$key]["tID"]);

                                            $encoded = json_encode($translation[$key]);
                                            $json_error = json_last_error();

                                            if ($json_error == JSON_ERROR_NONE) {
                                                $trData = array(
                                                    "translatedVerses" => $encoded,
                                                    "translateDone" => true
                                                );
                                                $this->translationModel->updateTranslation(
                                                    $trData,
                                                    array(
                                                        "trID" => $data["event"][0]->trID,
                                                        "tID" => $tID));
                                            } else {
                                                $error[] = __("error_occurred", array($tID));
                                            }
                                        }

                                        if (!isset($error)) {
                                            $chapters = [];
                                            for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                                $data["chapters"][$i] = [];
                                            }

                                            $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                            foreach ($chaptersDB as $chapter) {
                                                $tmp["trID"] = $chapter["trID"];
                                                $tmp["memberID"] = $chapter["memberID"];
                                                $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                                $tmp["checked"] = $chapter["checked"];

                                                $chapters[$chapter["chapter"]] = $tmp;
                                            }

                                            $chapters[$data["event"][0]->currentChapter]["checked"] = true;

                                            // Check if whole book is finished
                                            if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum, true)) {
                                                $this->eventModel->updateEvent([
                                                    "state" => EventStates::TRANSLATED,
                                                    "dateTo" => date("Y-m-d H:i:s", time())],
                                                    ["eventID" => $data["event"][0]->eventID]);

                                                $event = $this->eventRepo->get($data["event"][0]->eventID);
                                                $this->sendBookCompletedNotif($event);
                                            }

                                            $this->eventModel->updateChapter([
                                                "done" => true,
                                                "checked" => true
                                            ], [
                                                "eventID" => $data["event"][0]->eventID,
                                                "chapter" => $data["event"][0]->currentChapter
                                            ]);

                                            $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                            if (array_key_exists($data["event"][0]->currentChapter, $crCheck)) {
                                                $crCheck[$data["event"][0]->currentChapter]["done"] = 2;
                                            }

                                            $postdata = [
                                                "crCheck" => json_encode($crCheck),
                                            ];

                                            $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                            Url::redirect('events/');
                                        }
                                    }
                                } else {
                                    $error[] = __("empty_verses_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/SUN/Checker')
                            ->nest('page', 'Events/SUN/FinalReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/SUN/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/SUN/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    /**
     * View for Theo check and V-b-v check in ODB SUN event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerOdbSun($eventID, $memberID, $chapter)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEventsForSun(
            Session::get("memberID"), $eventID, $memberID, $chapter);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "sun") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                switch ($data["event"][0]->step) {
                    case EventSteps::THEO_CHECK:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                if (array_key_exists($data["event"][0]->currentChapter, $kwCheck)) {
                                    $kwCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                }

                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                $crCheck[$data["event"][0]->currentChapter] = [
                                    "memberID" => 0,
                                    "done" => 0
                                ];

                                $postdata = [
                                    "kwCheck" => json_encode($kwCheck),
                                    "crCheck" => json_encode($crCheck)
                                ];

                                $this->eventModel->updateTranslator($postdata, [
                                    "trID" => $data["event"][0]->trID
                                ]);
                                Url::redirect('events/');
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/ODBSUN/Checker')
                            ->nest('page', 'Events/ODBSUN/TheoCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::CONTENT_REVIEW:
                        $sourceText = $this->getOtherSourceText($data);

                        if ($sourceText !== false) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                foreach ($translation as $key => $chunk) {
                                    $translation[$key][EventMembers::TRANSLATOR]["verses"] = [
                                        ($key + 1) => $chunk[EventMembers::TRANSLATOR]["symbols"]
                                    ];

                                    $tID = $translation[$key]["tID"];
                                    unset($translation[$key]["tID"]);

                                    $encoded = json_encode($translation[$key]);
                                    $json_error = json_last_error();

                                    if ($json_error == JSON_ERROR_NONE) {
                                        $trData = array(
                                            "translatedVerses" => $encoded,
                                            "translateDone" => true
                                        );
                                        $this->translationModel->updateTranslation(
                                            $trData,
                                            array(
                                                "trID" => $data["event"][0]->trID,
                                                "tID" => $tID));
                                    } else {
                                        $error[] = __("error_occurred", array($tID));
                                    }
                                }

                                if (!isset($error)) {
                                    $chapters = [];
                                    for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["checked"] = $chapter["checked"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$data["event"][0]->currentChapter]["done"] = true;

                                    // Check if whole book is finished
                                    if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum, true)) {
                                        $this->eventModel->updateEvent([
                                            "state" => EventStates::TRANSLATED,
                                            "dateTo" => date("Y-m-d H:i:s", time())],
                                            ["eventID" => $data["event"][0]->eventID]);

                                        $event = $this->eventRepo->get($data["event"][0]->eventID);
                                        $this->sendBookCompletedNotif($event);
                                    }

                                    $this->eventModel->updateChapter(["checked" => true], ["eventID" => $data["event"][0]->eventID, "chapter" => $data["event"][0]->currentChapter]);

                                    $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                    if (array_key_exists($data["event"][0]->currentChapter, $crCheck)) {
                                        $crCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                    }

                                    $postdata = [
                                        "crCheck" => json_encode($crCheck),
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);
                                    Url::redirect('events/');
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/ODBSUN/Checker')
                            ->nest('page', 'Events/ODBSUN/ContentReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/ODBSUN/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/ODBSUN/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerRadio($eventID, $memberID, $chapter)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getMemberEventsForRadio(
            Session::get("memberID"), $eventID, $memberID, $chapter);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "rad") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::TRANSLATING || $data["event"][0]->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }
                $data["isCheckerPage"] = true;

                if ($data["event"][0]->step == EventSteps::PEER_REVIEW) {
                    $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                    // Get radio source
                    $sourceText = $this->getOtherSourceText($data);
                    if ($sourceText !== false) {
                        if (!array_key_exists("error", $sourceText)) {
                            $data = $sourceText;

                            $data["comments"] = EventUtil::getComments(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $data["event"][0]->trID,
                                $data["event"][0]->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $error[] = $sourceText["error"];
                            $data["error"] = $sourceText["error"];
                        }
                    } else {
                        $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                        $this->eventModel->updateTranslator(
                            [
                                "peerCheck" => json_encode($peerCheck)
                            ],
                            [
                                "trID" => $data["event"][0]->trID
                            ]
                        );
                        Url::redirect('events/checker-rad/' . $data["event"][0]->eventID .
                            "/" . $data["event"][0]->memberID . "/" . $data["event"][0]->currentChapter);
                    }

                    if (isset($_POST) && !empty($_POST)) {
                        $confirm_step = isset($_POST["confirm_step"]) ? $_POST["confirm_step"] : false;
                        if ($confirm_step) {
                            // Update Checker if it's empty
                            foreach ($translation as $tr) {
                                if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                    $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                    $tID = $tr["tID"];
                                    unset($tr["tID"]);
                                    $this->translationModel->updateTranslation(
                                        ["translatedVerses" => json_encode($tr)],
                                        ["tID" => $tID]
                                    );
                                }
                            }

                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;

                            $postdata = [
                                "peerCheck" => json_encode($peerCheck)
                            ];

                            $this->eventModel->updateTranslator($postdata, ["trID" => $data["event"][0]->trID]);

                            $chapters = [];
                            for ($i = 0; $i <= $data["event"][0]->chaptersNum; $i++) {
                                $data["chapters"][$i] = [];
                            }

                            // Set chapter checked
                            $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                            foreach ($chaptersDB as $chapter) {
                                $tmp["trID"] = $chapter["trID"];
                                $tmp["memberID"] = $chapter["memberID"];
                                $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                $tmp["done"] = $chapter["done"];
                                $tmp["checked"] = $chapter["checked"];

                                $chapters[$chapter["chapter"]] = $tmp;
                            }

                            $chapters[$data["event"][0]->currentChapter]["checked"] = true;
                            $this->eventModel->updateChapter(["checked" => true], [
                                "eventID" => $data["event"][0]->eventID,
                                "chapter" => $data["event"][0]->currentChapter]);

                            // Check if whole scripture is finished
                            if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum + 1, true)) {
                                $this->eventModel->updateEvent([
                                    "state" => EventStates::TRANSLATED,
                                    "dateTo" => date("Y-m-d H:i:s", time())],
                                    ["eventID" => $data["event"][0]->eventID]);

                                $event = $this->eventRepo->get($data["event"][0]->eventID);
                                $this->sendBookCompletedNotif($event);
                            }

                            Url::redirect('events');
                        }
                    }

                    $data["next_step"] = "continue_alt";

                    return View::make('Events/Radio/Translator')
                        ->nest('page', "Events/Radio/PeerReview")
                        ->shares("title", $title)
                        ->shares("data", $data)
                        ->shares("error", @$error);
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/Radio/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }


    /**
     * View for Keyword-Check and Peer-Review in Questions event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerObs($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["isPeerPage"] = false;
        $data["event"] = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"),
            $eventID,
            $memberID,
            $chapter
        );

        if (!empty($data["event"])) {
            $event = $data["event"][0];

            if ($event->bookProject != "obs") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($event->currentChapter > 0 ? $event->currentChapter : "")
                . " - " . $event->tLang
                . " - " . __($event->bookProject);

            if ($event->state == EventStates::TRANSLATING || $event->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $event->chunks = [];
                if (!empty($chapters)) {
                    $event->chunks = $chapters[0]["chunks"];
                }
                $data["isCheckerPage"] = true;
                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                $currentStep = $event->step;

                switch ($event->step) {
                    case EventSteps::PRAY:
                        // Get obs
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);

                        if (!$sourceTextObs) {
                            $otherCheck[$event->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-obs/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($event->currentChapter, $otherCheck)) {
                                    $otherCheck[$event->currentChapter]["done"] = 1;
                                }

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $event->trID]);

                                Url::redirect('events/checker-obs/' . $event->eventID .
                                    "/" . $event->memberID . "/" . $event->currentChapter);
                            }
                        }

                        $data["event"][0]->justStarted = true;
                        $data["next_step"] = EventSteps::KEYWORD_CHECK;

                        return View::make('Events/Obs/Translator')
                            ->nest('page', 'Events/Obs/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::KEYWORD_CHECK:

                        // Get obs
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);
                        $data["chunks"] = $this->apiModel->getObsChunks($sourceTextObs);

                        if ($sourceTextObs) {
                            $data["obs"] = $sourceTextObs->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-obs/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = $_POST["confirm_step"] ?? false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["obs"]);
                                if (!$chunks === false) {
                                    // Update Checker if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                            $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }

                                if (!isset($error)) {
                                    // 2 for PEER_REVIEW step
                                    $otherCheck[$event->currentChapter]["done"] = 2;

                                    $peerCheck = (array)json_decode($event->peerCheck, true);
                                    if (!array_key_exists($event->currentChapter, $peerCheck)) {
                                        $peerCheck[$event->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "otherCheck" => json_encode($otherCheck),
                                        "peerCheck" => json_encode($peerCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/checker-obs/' . $event->eventID .
                                        "/" . $event->memberID . "/" . $event->currentChapter);
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::PEER_REVIEW . "_obs";

                        return View::make('Events/Obs/Translator')
                            ->nest('page', 'Events/Obs/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::PEER_REVIEW:

                        // Get obs
                        $sourceTextObs = $this->resourcesRepo->getObs($event->resLangID, $event->currentChapter);
                        $data["chunks"] = $this->apiModel->getObsChunks($sourceTextObs);

                        if ($sourceTextObs) {
                            $data["obs"] = $sourceTextObs->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-obs/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($event->peerCheck, true);

                                if ($event->peer == 1) {
                                    if (isset($peerCheck[$event->currentChapter]) &&
                                        $peerCheck[$event->currentChapter]["done"]) {
                                        // 3 for chapter finished
                                        $otherCheck[$event->currentChapter]["done"] = 3;

                                        // Cleanup chapter notifications for this step
                                        $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                            ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                                return $item->eventID == $eventID
                                                    && $item->currentChapter == $chapter
                                                    && $item->step == $currentStep;
                                            });

                                        $myNotifications->each(function($item) {
                                            $item->delete();
                                        });

                                        $this->eventModel->updateTranslator(
                                            ["otherCheck" => json_encode($otherCheck)],
                                            ["trID" => $event->trID]);

                                        $chapters = [];
                                        for ($i = 1; $i <= $event->chaptersNum; $i++) {
                                            $data["chapters"][$i] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($event->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];
                                            $tmp["checked"] = $chapter["checked"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        $chapters[$event->currentChapter]["checked"] = true;
                                        $this->eventModel->updateChapter(["checked" => true], [
                                            "eventID" => $event->eventID,
                                            "chapter" => $event->currentChapter]);

                                        // Check if whole scripture is finished
                                        if ($this->checkBookFinished($chapters, $event->chaptersNum, true)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $event->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event, 2);
                                        }

                                        Url::redirect('events');
                                    } else {
                                        $error[] = __("checker_not_ready_error");
                                    }
                                } else {
                                    $peerCheck[$event->currentChapter]["done"] = 1;
                                    $this->eventModel->updateTranslator(
                                        ["peerCheck" => json_encode($peerCheck)],
                                        ["trID" => $event->trID]);

                                    $response["success"] = true;
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        if ($data["event"][0]->peer == 1)
                            $page = "Events/Obs/PeerReview";
                        else {
                            $page = "Events/Obs/CheckerPeerReview";
                            $data["isPeerPage"] = true;
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Obs/Translator')
                            ->nest('page', $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/Obs/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * View for Keyword-Check and Peer-Review in Questions event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerBc($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["isPeerPage"] = false;
        $data["event"] = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"),
            $eventID,
            $memberID,
            $chapter
        );

        if (!empty($data["event"])) {
            $event = $data["event"][0];

            if ($event->bookProject != "bc") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($event->currentChapter > 0 ? $event->currentChapter : "")
                . " - " . $event->tLang
                . " - " . __($event->bookProject);

            if ($event->state == EventStates::TRANSLATING || $event->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $event->chunks = [];
                if (!empty($chapters)) {
                    $event->chunks = $chapters[0]["chunks"];
                }
                $data["isCheckerPage"] = true;
                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                $currentStep = $event->step;

                switch ($event->step) {
                    case EventSteps::PRAY:
                        // Get bc
                        $sourceTextBc = $this->resourcesRepo->getBcSource(
                            $event->resLangID,
                            $event->bookCode,
                            $event->sort,
                            $event->currentChapter
                        );

                        if (!$sourceTextBc) {
                            $otherCheck[$event->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-bc/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($event->currentChapter, $otherCheck)) {
                                    $otherCheck[$event->currentChapter]["done"] = 1;
                                }

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $event->trID]);

                                Url::redirect('events/checker-bc/' . $event->eventID .
                                    "/" . $event->memberID . "/" . $event->currentChapter);
                            }
                        }

                        $data["event"][0]->justStarted = true;
                        $data["next_step"] = EventSteps::KEYWORD_CHECK;

                        return View::make('Events/Bc/Translator')
                            ->nest('page', 'Events/Bc/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::KEYWORD_CHECK:

                        // Get obs
                        $sourceTextBc = $this->resourcesRepo->getBcSource(
                            $event->resLangID,
                            $event->bookCode,
                            $event->sort,
                            $event->currentChapter
                        );
                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceTextBc);

                        if ($sourceTextBc) {
                            $data["bc"] = $sourceTextBc->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-bc/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = $_POST["confirm_step"] ?? false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["bc"]);
                                if (!$chunks === false) {
                                    // Update Checker if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                            $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }

                                if (!isset($error)) {
                                    // 2 for PEER_REVIEW step
                                    $otherCheck[$event->currentChapter]["done"] = 2;

                                    $peerCheck = (array)json_decode($event->peerCheck, true);
                                    if (!array_key_exists($event->currentChapter, $peerCheck)) {
                                        $peerCheck[$event->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "otherCheck" => json_encode($otherCheck),
                                        "peerCheck" => json_encode($peerCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/checker-bc/' . $event->eventID .
                                        "/" . $event->memberID . "/" . $event->currentChapter);
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::PEER_REVIEW . "_bc";

                        return View::make('Events/Bc/Translator')
                            ->nest('page', 'Events/Bc/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::PEER_REVIEW:

                        // Get obs
                        $sourceTextBc = $this->resourcesRepo->getBcSource(
                            $event->resLangID,
                            $event->bookCode,
                            $event->sort,
                            $event->currentChapter
                        );
                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceTextBc);

                        if ($sourceTextBc) {
                            $data["bc"] = $sourceTextBc->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-bc/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($event->peerCheck, true);

                                if ($event->peer == 1) {
                                    if (isset($peerCheck[$event->currentChapter]) &&
                                        $peerCheck[$event->currentChapter]["done"]) {
                                        // 3 for chapter finished
                                        $otherCheck[$event->currentChapter]["done"] = 3;

                                        // Cleanup chapter notifications for this step
                                        $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                            ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                                return $item->eventID == $eventID
                                                    && $item->currentChapter == $chapter
                                                    && $item->step == $currentStep;
                                            });

                                        $myNotifications->each(function($item) {
                                            $item->delete();
                                        });

                                        $this->eventModel->updateTranslator(
                                            ["otherCheck" => json_encode($otherCheck)],
                                            ["trID" => $event->trID]);

                                        $chapters = [];
                                        for ($i = 1; $i <= $event->chaptersNum; $i++) {
                                            $data["chapters"][$i] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($event->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];
                                            $tmp["checked"] = $chapter["checked"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        $chapters[$event->currentChapter]["checked"] = true;
                                        $this->eventModel->updateChapter(["checked" => true], [
                                            "eventID" => $event->eventID,
                                            "chapter" => $event->currentChapter]);

                                        // Check if whole scripture is finished
                                        if ($this->checkBookFinished($chapters, $event->chaptersNum, true)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $event->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event, 2);
                                        }

                                        Url::redirect('events');
                                    } else {
                                        $error[] = __("checker_not_ready_error");
                                    }
                                } else {
                                    $peerCheck[$event->currentChapter]["done"] = 1;
                                    $this->eventModel->updateTranslator(
                                        ["peerCheck" => json_encode($peerCheck)],
                                        ["trID" => $event->trID]);

                                    $response["success"] = true;
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        if ($data["event"][0]->peer == 1)
                            $page = "Events/Bc/PeerReview";
                        else {
                            $page = "Events/Bc/CheckerPeerReview";
                            $data["isPeerPage"] = true;
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Bc/Translator')
                            ->nest('page', $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/Bc/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * View for Keyword-Check and Peer-Review in Questions event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerBca($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["isPeerPage"] = false;
        $data["event"] = $this->eventModel->getMemberEventsForOther(
            Session::get("memberID"),
            $eventID,
            $memberID,
            $chapter
        );

        if (!empty($data["event"])) {
            $event = $data["event"][0];
            $eventObj = $this->eventRepo->get($event->eventID);

            if ($event->bookProject != "bca") {
                Url::redirect("events/");
            }

            $title = $data["event"][0]->name
                . " " . ($event->currentChapter > 0 ? $event->currentChapter : "")
                . " - " . $event->tLang
                . " - " . __($event->bookProject);

            if ($event->state == EventStates::TRANSLATING || $event->state == EventStates::TRANSLATED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $event->chunks = [];
                if (!empty($chapters)) {
                    $event->chunks = $chapters[0]["chunks"];
                }
                $data["isCheckerPage"] = true;
                $otherCheck = (array)json_decode($data["event"][0]->otherCheck, true);
                $currentStep = $event->step;

                switch ($event->step) {
                    case EventSteps::PRAY:
                        // Get Articles
                        $data["word"] = $eventObj->words->filter(function($word) use ($event) {
                            return $word->wordID == $event->currentChapter;
                        })->first()->word;

                        $sourceArticle = $this->resourcesRepo->getBcArticlesSource(
                            $event->resLangID,
                            $data["word"]
                        );

                        if (!$sourceArticle) {
                            $otherCheck[$event->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-bca/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($event->currentChapter, $otherCheck)) {
                                    $otherCheck[$event->currentChapter]["done"] = 1;
                                }

                                $this->eventModel->updateTranslator([
                                    "otherCheck" => json_encode($otherCheck)
                                ], ["trID" => $event->trID]);

                                Url::redirect('events/checker-bca/' . $event->eventID .
                                    "/" . $event->memberID . "/" . $event->currentChapter);
                            }
                        }

                        $data["event"][0]->justStarted = true;
                        $data["next_step"] = EventSteps::KEYWORD_CHECK;

                        return View::make('Events/Bca/Translator')
                            ->nest('page', 'Events/Bca/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::KEYWORD_CHECK:
                        // Get Articles
                        $data["word"] = $eventObj->words->filter(function($word) use ($event) {
                            return $word->wordID == $event->currentChapter;
                        })->first()->word;

                        $sourceArticle = $this->resourcesRepo->getBcArticlesSource(
                            $event->resLangID,
                            $data["word"]
                        );
                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceArticle);

                        if ($sourceArticle) {
                            $data["bca"] = $sourceArticle->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-bca/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $confirm_step = $_POST["confirm_step"] ?? false;
                            if ($confirm_step) {
                                $chunks = isset($_POST["chunks"]) ? (array)$_POST["chunks"] : [];
                                $chunks = $this->apiModel->testChunkMd($chunks, $data["bca"]);
                                if (!$chunks === false) {
                                    // Update Checker if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::CHECKER]["verses"])) {
                                            $tr[EventMembers::CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }
                                } else {
                                    $error[] = __("wrong_chunks_error");
                                }

                                if (!isset($error)) {
                                    // 2 for PEER_REVIEW step
                                    $otherCheck[$event->currentChapter]["done"] = 2;

                                    $peerCheck = (array)json_decode($event->peerCheck, true);
                                    if (!array_key_exists($event->currentChapter, $peerCheck)) {
                                        $peerCheck[$event->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }

                                    $postdata = [
                                        "otherCheck" => json_encode($otherCheck),
                                        "peerCheck" => json_encode($peerCheck)
                                    ];

                                    $this->eventModel->updateTranslator($postdata, ["trID" => $event->trID]);
                                    Url::redirect('events/checker-bca/' . $event->eventID .
                                        "/" . $event->memberID . "/" . $event->currentChapter);
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::PEER_REVIEW . "_bca";

                        return View::make('Events/Bca/Translator')
                            ->nest('page', 'Events/Bca/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;

                    case EventSteps::PEER_REVIEW:
                        // Get Articles
                        $data["word"] = $eventObj->words->filter(function($word) use ($event) {
                            return $word->wordID == $event->currentChapter;
                        })->first()->word;

                        $sourceArticle = $this->resourcesRepo->getBcArticlesSource(
                            $event->resLangID,
                            $data["word"]
                        );
                        $data["chunks"] = $this->apiModel->getResourceChunks($sourceArticle);

                        if ($sourceArticle) {
                            $data["bca"] = $sourceArticle->chunks;

                            $data["comments"] = EventUtil::getComments(
                                $event->eventID,
                                $event->currentChapter);

                            $translationData = $this->translationModel->getEventTranslation(
                                $event->trID,
                                $event->currentChapter);
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $otherCheck[$data["event"][0]->currentChapter]["done"] = 3;
                            $this->eventModel->updateTranslator(["otherCheck" => json_encode($otherCheck)], ["trID" => $event->trID]);
                            Url::redirect('events/checker-bca/' . $event->eventID .
                                "/" . $event->memberID . "/" . $event->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($event->peerCheck, true);

                                if ($event->peer == 1) {
                                    if (isset($peerCheck[$event->currentChapter]) &&
                                        $peerCheck[$event->currentChapter]["done"]) {
                                        // 3 for chapter finished
                                        $otherCheck[$event->currentChapter]["done"] = 3;

                                        // Cleanup chapter notifications for this step
                                        $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                            ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                                return $item->eventID == $eventID
                                                    && $item->currentChapter == $chapter
                                                    && $item->step == $currentStep;
                                            });

                                        $myNotifications->each(function($item) {
                                            $item->delete();
                                        });

                                        $this->eventModel->updateTranslator(
                                            ["otherCheck" => json_encode($otherCheck)],
                                            ["trID" => $event->trID]);

                                        $chapters = [];
                                        for ($i = 1; $i <= $eventObj->words->count(); $i++) {
                                            $data["chapters"][$i] = [];
                                        }

                                        $chaptersDB = $this->eventModel->getChapters($event->eventID);

                                        foreach ($chaptersDB as $chapter) {
                                            $tmp["trID"] = $chapter["trID"];
                                            $tmp["memberID"] = $chapter["memberID"];
                                            $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                            $tmp["done"] = $chapter["done"];
                                            $tmp["checked"] = $chapter["checked"];

                                            $chapters[$chapter["chapter"]] = $tmp;
                                        }

                                        $chapters[$event->currentChapter]["checked"] = true;
                                        $this->eventModel->updateChapter(["checked" => true], [
                                            "eventID" => $event->eventID,
                                            "chapter" => $event->currentChapter]);

                                        // Check if whole scripture is finished
                                        if ($this->checkBookFinished($chapters, $eventObj->words->count(), true)) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::TRANSLATED,
                                                "dateTo" => date("Y-m-d H:i:s", time())],
                                                ["eventID" => $event->eventID]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event, 2);
                                        }

                                        Url::redirect('events');
                                    } else {
                                        $error[] = __("checker_not_ready_error");
                                    }
                                } else {
                                    $peerCheck[$event->currentChapter]["done"] = 1;
                                    $this->eventModel->updateTranslator(
                                        ["peerCheck" => json_encode($peerCheck)],
                                        ["trID" => $event->trID]);

                                    $response["success"] = true;
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        if ($data["event"][0]->peer == 1)
                            $page = "Events/Bca/PeerReview";
                        else {
                            $page = "Events/Bca/CheckerPeerReview";
                            $data["isPeerPage"] = true;
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Bca/Translator')
                            ->nest('page', $page)
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $error[] = __("checker_event_error");
                $title = "Error";
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        return View::make('Events/Bca/Translator')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * View for revision page
     * @url /events/checker-revision
     * @param $eventID
     * @return View
     */
    public function checkerRevision($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getRevisionMemberEvents(
            Session::get("memberID"),
            $eventID
        );

        if (!empty($data["event"])) {
            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L2_CHECK || $data["event"][0]->state == EventStates::L2_CHECKED) {
                if ($data["event"][0]->step == EventCheckSteps::NONE)
                    Url::redirect("events/information-revision/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PRAY:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL2Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);
                            Url::redirect('events/checker-revision/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $postdata = [
                                    "step" => EventCheckSteps::CONSUME,
                                    "currentChapter" => $sourceText["currentChapter"]
                                ];
                                $this->eventModel->updateL2Checker($postdata, [
                                    "l2chID" => $data["event"][0]->l2chID
                                ]);
                                Url::redirect('events/checker-revision/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventCheckSteps::CONSUME;
                        $data["event"][0]->justStarted = $data["event"][0]->peerCheck == "";

                        return View::make('Events/Revision/Checker')
                            ->nest('page', 'Events/Revision/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::CONSUME:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL2Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);
                            Url::redirect('events/checker-revision/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $this->eventModel->updateL2Checker([
                                    "step" => EventCheckSteps::SELF_CHECK
                                ], [
                                    "l2chID" => $data["event"][0]->l2chID
                                ]);
                                Url::redirect('events/checker-revision/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventCheckSteps::SELF_CHECK;

                        return View::make('Events/Revision/Checker')
                            ->nest('page', 'Events/Revision/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::SELF_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);
                        $nextChapter = 0;
                        $isMajorMode = $data["event"][0]->revisionMode == RevisionMode::MAJOR;

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                // Check if the member has another chapter to check
                                // then redirect to preparation page
                                $nextChapterDB = $this->eventModel->getNextChapter(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->memberID,
                                    "l2");
                                if (!empty($nextChapterDB) && isset($nextChapterDB[1]))
                                    $nextChapter = $nextChapterDB[1]->chapter;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL2Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);
                            Url::redirect('events/checker-revision/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $submitStep = isset($_POST["submitStep"]) && $_POST["submitStep"];
                            $chapterLink = $submitStep ? "/" . $data["event"][0]->currentChapter : "";

                            if (isset($_POST["confirm_step"])) {
                                // Update L2 if it's empty
                                foreach ($translation as $tr) {
                                    if (empty($tr[EventMembers::L2_CHECKER]["verses"])) {
                                        $tr[EventMembers::L2_CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                        $tID = $tr["tID"];
                                        unset($tr["tID"]);
                                        $this->translationModel->updateTranslation(
                                            ["translatedVerses" => json_encode($tr)],
                                            ["tID" => $tID]
                                        );
                                    }
                                }

                                $chapters = [];
                                for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                    $data["chapters"][$i] = [];
                                }

                                $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                foreach ($chaptersDB as $chapter) {
                                    $tmp["trID"] = $chapter["trID"];
                                    $tmp["memberID"] = $chapter["memberID"];
                                    $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                    $tmp["done"] = $chapter["done"];
                                    $tmp["l2memberID"] = $chapter["l2memberID"];
                                    $tmp["l2chID"] = $chapter["l2chID"];
                                    $tmp["l2checked"] = $chapter["l2checked"];

                                    $chapters[$chapter["chapter"]] = $tmp;
                                }

                                $this->eventModel->updateChapter([
                                    "l2checked" => true
                                ], [
                                    "eventID" => $data["event"][0]->eventID,
                                    "chapter" => $data["event"][0]->currentChapter
                                ]);

                                $chapters[$data["event"][0]->currentChapter]["l2checked"] = true;

                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);

                                if ($isMajorMode) {
                                    if (!isset($peerCheck[$data["event"][0]->currentChapter])) {
                                        $peerCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }
                                } else {
                                    if (!isset($kwCheck[$data["event"][0]->currentChapter])) {
                                        $kwCheck[$data["event"][0]->currentChapter] = [
                                            "memberID" => 0,
                                            "done" => 0
                                        ];
                                    }
                                }

                                $postdata = [
                                    "step" => EventSteps::NONE,
                                    "currentChapter" => 0,
                                    "peerCheck" => json_encode($peerCheck),
                                    "kwCheck" => json_encode($kwCheck)
                                ];

                                if ($nextChapter > 0) {
                                    $postdata["step"] = EventSteps::PRAY;
                                    $postdata["currentChapter"] = $nextChapter;
                                }

                                $this->eventModel->updateL2Checker($postdata, [
                                    "l2chID" => $data["event"][0]->l2chID
                                ]);

                                Url::redirect('events/checker-revision/' . $data["event"][0]->eventID . $chapterLink);
                            }
                        }

                        $data["next_step"] = $isMajorMode ? EventCheckSteps::PEER_REVIEW : EventCheckSteps::KEYWORD_CHECK;
                        $data["nextChapter"] = $nextChapter;

                        return View::make('Events/Revision/Checker')
                            ->nest('page', 'Events/Revision/SelfCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Revision/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Revision/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerRevisionContinue($eventID, $chapter) {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getRevisionMemberEvents(
            Session::get("memberID"),
            $eventID,
            $chapter,
            true
        );

        if (!empty($data["event"])) {
            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L2_CHECK || $data["event"][0]->state == EventStates::L2_CHECKED) {
                if ($data["event"][0]->step == EventCheckSteps::NONE)
                    Url::redirect("events/information-revision/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                $currentStep = $data["event"][0]->step;

                switch ($currentStep) {
                    case EventSteps::PEER_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                if ($peerCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $peerCheck[$data["event"][0]->currentChapter]["done"] = 2;
                                    $kwCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                    $postdata = [
                                        "peerCheck" => json_encode($peerCheck),
                                        "kwCheck" => json_encode($kwCheck)
                                    ];

                                    $this->eventModel->updateL2Checker($postdata, ["l2chID" => $data["event"][0]->l2chID]);

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $chapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    Url::redirect('events/checker-revision/'
                                        . $data["event"][0]->eventID
                                        . '/' . $data["event"][0]->currentChapter);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::KEYWORD_CHECK;

                        return View::make('Events/Revision/Checker')
                            ->nest('page', 'Events/Revision/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::KEYWORD_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                if ($kwCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $kwCheck[$data["event"][0]->currentChapter]["done"] = 2;
                                    $crCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                    $postdata = [
                                        "kwCheck" => json_encode($kwCheck),
                                        "crCheck" => json_encode($crCheck)
                                    ];

                                    $this->eventModel->updateL2Checker($postdata, ["l2chID" => $data["event"][0]->l2chID]);

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $chapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    Url::redirect('events/checker-revision/'
                                        . $data["event"][0]->eventID
                                        . '/' . $data["event"][0]->currentChapter);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventSteps::CONTENT_REVIEW;

                        return View::make('Events/Revision/Checker')
                            ->nest('page', 'Events/Revision/KeywordCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventSteps::CONTENT_REVIEW:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            Url::redirect('events');
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            $_POST = Gump::xss_clean($_POST);

                            if (isset($_POST["confirm_step"])) {
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                if ($crCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $crCheck[$data["event"][0]->currentChapter]["done"] = 2;
                                    $postdata = [
                                        "crCheck" => json_encode($crCheck)
                                    ];

                                    $this->eventModel->updateL2Checker($postdata, ["l2chID" => $data["event"][0]->l2chID]);

                                    // Check if the whole book was checked and set its state to L2_CHECKED
                                    $chapters = [];
                                    $events = $this->eventModel->getMembersForRevisionEvent($data["event"][0]->eventID);

                                    foreach ($events as $event) {
                                        $cr = (array)json_decode($event["crCheck"], true);
                                        if (!empty($cr)) {
                                            $chapters += $cr;
                                        }
                                    }

                                    if (sizeof($chapters) == $data["event"][0]->chaptersNum) {
                                        $allDone = true;
                                        foreach ($chapters as $chapter) {
                                            if ($chapter["done"] == 0) {
                                                $allDone = false;
                                                break;
                                            }
                                        }

                                        if ($allDone) {
                                            $this->eventModel->updateEvent([
                                                "state" => EventStates::L2_CHECKED
                                            ], [
                                                "eventID" => $data["event"][0]->eventID
                                            ]);

                                            $event = $this->eventRepo->get($data["event"][0]->eventID);
                                            $this->sendBookCompletedNotif($event, 2);
                                        }
                                    }

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $chapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $chapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    Url::redirect('events/checker-revision/'
                                        . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Revision/Checker')
                            ->nest('page', 'Events/Revision/ContentReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Revision/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Revision/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    /**
     * View for approving check in Revision event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerRevisionApprover($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEventsForRevisionChecker(
            Session::get("memberID"),
            $eventID,
            $memberID,
            $chapter
        );

        if (!empty($data["event"])) {
            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L2_CHECK || $data["event"][0]->state == EventStates::L2_CHECKED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                if (in_array(
                    $data["event"][0]->step,
                    [
                        EventCheckSteps::PEER_REVIEW,
                        EventCheckSteps::KEYWORD_CHECK,
                        EventCheckSteps::CONTENT_REVIEW
                    ])
                ) {
                    $sourceText = $this->getScriptureSourceText($data);

                    if (!empty($sourceText)) {
                        if (!array_key_exists("error", $sourceText)) {
                            $data = $sourceText;

                            $data["comments"] = EventUtil::getComments(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter);

                            $translationData = $this->translationModel->getEventTranslationByEventID(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $error[] = $sourceText["error"];
                            $data["error"] = $sourceText["error"];
                        }
                    } else {
                        Url::redirect('events');
                    }

                    if (isset($_POST) && !empty($_POST)) {
                        $_POST = Gump::xss_clean($_POST);

                        if (isset($_POST["confirm_step"])) {
                            if ($data["event"][0]->step == EventCheckSteps::PEER_REVIEW) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                $postdata["peerCheck"] = json_encode($peerCheck);
                            } elseif ($data["event"][0]->step == EventCheckSteps::KEYWORD_CHECK) {
                                $kwCheck = (array)json_decode($data["event"][0]->kwCheck, true);
                                $kwCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                $postdata["kwCheck"] = json_encode($kwCheck);
                            } else {
                                $crCheck = (array)json_decode($data["event"][0]->crCheck, true);
                                $crCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                $postdata["crCheck"] = json_encode($crCheck);
                            }

                            $this->eventModel->updateL2Checker($postdata, array("l2chID" => $data["event"][0]->l2chID));

                            $response["success"] = true;
                            echo json_encode($response);
                            exit;
                        }
                    }
                } else {
                    $error[] = __("checker_translator_not_ready_error");
                }
            } else {
                $error[] = __("translator_event_finished_success");
            }
        } else {
            $error[] = __("checker_event_error");
            $title = "Error";
        }

        $page = null;
        if (!isset($error)) {
            switch ($data["event"][0]->step) {
                case EventCheckSteps::PEER_REVIEW:
                    $page = "Events/Revision/CheckerPeerReview";
                    break;

                case EventCheckSteps::KEYWORD_CHECK:
                    $page = "Events/Revision/CheckerKeywordCheck";
                    break;

                case EventCheckSteps::CONTENT_REVIEW:
                    $page = "Events/Revision/CheckerContentReview";
                    break;

                default:
                    $page = null;
                    break;
            }
        }

        $data["next_step"] = "continue_alt";

        $view = View::make('Events/Revision/Checker')
            ->shares("title", $title)
            ->shares("data", $data)
            ->shares("error", @$error);

        if ($page != null) $view->nest('page', $page);

        return $view;
    }

    public function checkerSunRevision($eventID) {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getRevisionMemberEvents(
            Session::get("memberID"),
            $eventID
        );

        if (!empty($data["event"])) {
            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L2_CHECK || $data["event"][0]->state == EventStates::L2_CHECKED) {
                if ($data["event"][0]->step == EventCheckSteps::NONE)
                    Url::redirect("events/information-sun-revision/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PRAY:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL2Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);
                            Url::redirect('events/checker-sun-revision/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $postdata = [
                                    "step" => EventCheckSteps::CONSUME,
                                    "currentChapter" => $sourceText["currentChapter"]
                                ];
                                $this->eventModel->updateL2Checker($postdata, [
                                    "l2chID" => $data["event"][0]->l2chID
                                ]);
                                Url::redirect('events/checker-sun-revision/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventCheckSteps::CONSUME;

                        return View::make('Events/RevisionSun/Checker')
                            ->nest('page', 'Events/RevisionSun/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::CONSUME:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL2Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);
                            Url::redirect('events/checker-sun-revision/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {

                                $this->eventModel->updateL2Checker([
                                    "step" => EventCheckSteps::SELF_CHECK
                                ], [
                                    "l2chID" => $data["event"][0]->l2chID
                                ]);
                                Url::redirect('events/checker-sun-revision/' . $data["event"][0]->eventID);
                            }
                        }

                        $data["next_step"] = EventCheckSteps::SELF_CHECK . "_sun";

                        return View::make('Events/RevisionSun/Checker')
                            ->nest('page', 'Events/RevisionSun/Consume')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::SELF_CHECK:
                        $sourceText = $this->getScriptureSourceText($data);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL2Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);
                            Url::redirect('events/checker-sun-revision/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                // Update L2 if it's empty
                                foreach ($translation as $tr) {
                                    if (empty($tr[EventMembers::L2_CHECKER]["verses"])) {
                                        $tr[EventMembers::L2_CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                        $tID = $tr["tID"];
                                        unset($tr["tID"]);
                                        $this->translationModel->updateTranslation(
                                            ["translatedVerses" => json_encode($tr)],
                                            ["tID" => $tID]
                                        );
                                    }
                                }

                                $chapters = [];
                                for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                    $data["chapters"][$i] = [];
                                }

                                $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                foreach ($chaptersDB as $chapter) {
                                    $tmp["trID"] = $chapter["trID"];
                                    $tmp["memberID"] = $chapter["memberID"];
                                    $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                    $tmp["done"] = $chapter["done"];
                                    $tmp["l2memberID"] = $chapter["l2memberID"];
                                    $tmp["l2chID"] = $chapter["l2chID"];
                                    $tmp["l2checked"] = $chapter["l2checked"];

                                    $chapters[$chapter["chapter"]] = $tmp;
                                }

                                $this->eventModel->updateChapter([
                                    "l2checked" => true
                                ], [
                                    "eventID" => $data["event"][0]->eventID,
                                    "chapter" => $data["event"][0]->currentChapter
                                ]);

                                $chapters[$data["event"][0]->currentChapter]["l2checked"] = true;

                                // Check if the member has another chapter to check
                                // then redirect to preparation page
                                $nextChapter = 0;
                                $nextChapterDB = $this->eventModel->getNextChapter(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->memberID,
                                    "l2");
                                if (!empty($nextChapterDB))
                                    $nextChapter = $nextChapterDB[0]->chapter;

                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                if (!array_key_exists($data["event"][0]->currentChapter, $peerCheck)) {
                                    $peerCheck[$data["event"][0]->currentChapter] = [
                                        "memberID" => 0,
                                        "done" => 0
                                    ];
                                }

                                $postdata = [
                                    "step" => EventSteps::NONE,
                                    "currentChapter" => 0,
                                    "peerCheck" => json_encode($peerCheck)
                                ];

                                if ($nextChapter > 0) {
                                    $postdata["step"] = EventSteps::PRAY;
                                    $postdata["currentChapter"] = $nextChapter;
                                }

                                $this->eventModel->updateL2Checker($postdata, [
                                    "l2chID" => $data["event"][0]->l2chID
                                ]);

                                if ($nextChapter > 0)
                                    Url::redirect('events/checker-sun-revision/' . $data["event"][0]->eventID);
                                else
                                    Url::redirect('events/');
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/RevisionSun/Checker')
                            ->nest('page', 'Events/RevisionSun/PeerCheck')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/RevisionSun/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/RevisionSun/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    /**
     * View for Theological check in Sun Revision event
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return View
     */
    public function checkerSunRevisionContinue($eventID, $memberID, $chapter)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getMemberEventsForSunRevisionChecker(
            Session::get("memberID"),
            $eventID,
            $memberID,
            $chapter
        );

        if (!empty($data["event"])) {
            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L2_CHECK || $data["event"][0]->state == EventStates::L2_CHECKED) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                if ($data["event"][0]->step == EventCheckSteps::PEER_REVIEW) {
                    $sourceText = $this->getScriptureSourceText($data);

                    if (!empty($sourceText)) {
                        if (!array_key_exists("error", $sourceText)) {
                            $data = $sourceText;

                            $data["comments"] = EventUtil::getComments(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter);

                            $translationData = $this->translationModel->getEventTranslationByEventID(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;
                        } else {
                            $error[] = $sourceText["error"];
                            $data["error"] = $sourceText["error"];
                        }
                    } else {
                        Url::redirect('events');
                    }

                    if (isset($_POST) && !empty($_POST)) {
                        if (isset($_POST["confirm_step"])) {
                            $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                            if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)) {
                                $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                            }

                            $postdata = [
                                "peerCheck" => json_encode($peerCheck)
                            ];

                            $this->eventModel->updateL2Checker($postdata, [
                                "l2chID" => $data["event"][0]->l2chID
                            ]);

                            // Check if the whole book was checked and set its state to L2_CHECKED
                            $chapters = [];
                            $events = $this->eventModel->getMembersForRevisionEvent($data["event"][0]->eventID);

                            foreach ($events as $event) {
                                $peer = (array)json_decode($event["peerCheck"], true);
                                if (!empty($peer)) {
                                    $chapters += $peer;
                                }
                            }

                            if (sizeof($chapters) == $data["event"][0]->chaptersNum) {
                                $allDone = true;
                                foreach ($chapters as $chapter) {
                                    if ($chapter["done"] == 0) {
                                        $allDone = false;
                                        break;
                                    }
                                }

                                if ($allDone) {
                                    $this->eventModel->updateEvent([
                                        "state" => EventStates::L2_CHECKED
                                    ], [
                                        "eventID" => $data["event"][0]->eventID
                                    ]);

                                    $event = $this->eventRepo->get($data["event"][0]->eventID);
                                    $this->sendBookCompletedNotif($event, 2);
                                }
                            }

                            Url::redirect('events');
                        }
                    }

                    $data["next_step"] = "continue_alt";

                    return View::make('Events/RevisionSun/Checker')
                        ->nest('page', 'Events/RevisionSun/TheoCheck')
                        ->shares("title", $title)
                        ->shares("data", $data)
                        ->shares("error", @$error);
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/RevisionSun/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/RevisionSun/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerNotesReview($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isChecker"] = false;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getMemberEventsForCheckerL3(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "tn") {
                if (in_array($data["event"][0]->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/checker-review/" . $eventID);
                else
                    Url::redirect("events/checker-" . $data["event"][0]->bookProject . "-review/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > -1 ? ($data["event"][0]->currentChapter == 0
                    ? __("front") : $data["event"][0]->currentChapter) : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if (($data["event"][0]->state == EventStates::L3_CHECK
                || $data["event"][0]->state == EventStates::COMPLETE)) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-tn-review/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();
                $currentStep = $data["event"][0]->step;
                $currentChapter = $data["event"][0]->currentChapter;

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PRAY:

                        $data["currentChapter"] = $data["event"][0]->currentChapter;
                        if ($data["event"][0]->currentChapter < 0) {
                            $nextChapter = $this->eventModel->getNextChapter(
                                $data["event"][0]->eventID,
                                $data["event"][0]->memberID,
                                "l3");

                            if (!empty($nextChapter)) {
                                $data["currentChapter"] = $nextChapter[0]->chapter;
                            } else {
                                $postdata = [
                                    "step" => EventCheckSteps::NONE,
                                    "currentChapter" => -1
                                ];
                                $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                Url::redirect('events/checker-tn-review/' . $data["event"][0]->eventID);
                            }
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $peerCheck[$data["currentChapter"]] = [
                                    "memberID" => 0,
                                    "done" => 0
                                ];

                                $postdata = [
                                    "step" => EventCheckSteps::PEER_REVIEW_L3,
                                    "currentChapter" => $data["currentChapter"],
                                    "peerCheck" => json_encode($peerCheck)
                                ];
                                $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                Url::redirect('events/checker-tn-review/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->peerCheck == "";
                        $data["next_step"] = EventCheckSteps::PEER_REVIEW_L3;

                        return View::make('Events/ReviewNotes/Checker')
                            ->nest('page', 'Events/ReviewNotes/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::PEER_REVIEW_L3:
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                        $data["currentChapter"] = $data["event"][0]->currentChapter;

                        // Get related ulb project
                        $ulbProject = $this->eventModel->getProject(["projects.projectID"], [
                            ["projects.glID", $data["event"][0]->glID],
                            ["projects.gwLang", $data["event"][0]->gwLang],
                            ["projects.targetLang", $data["event"][0]->targetLang],
                            ["projects.bookProject", "ulb"]
                        ]);

                        if (!empty($ulbProject))
                            $ulbEvent = $this->eventModel->getEvent(null, $ulbProject[0]->projectID, $data["event"][0]->bookCode);

                        if (empty($ulbEvent) || $ulbEvent[0]->state != EventStates::COMPLETE)
                            $error[] = __("l2_l3_event_notexist_error");

                        if (!isset($error)) {
                            // Get ulb translation
                            $ulbTranslationData = $this->translationModel->getEventTranslationByEventID(
                                $ulbEvent[0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $ulbTranslation = ["l2" => [], "l3" => []];

                            foreach ($ulbTranslationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);

                                $ulbTranslation["l2"] += $arr[EventMembers::L2_CHECKER]["verses"];
                                $ulbTranslation["l3"] += $arr[EventMembers::L3_CHECKER]["verses"];
                            }
                            $data["ulb_translation"] = $ulbTranslation;

                            // Remove footnotes to avoid comparison errors
                            $data["ulb_translation"]["l2"] = array_map(function ($elm) {
                                return preg_replace("/<span.*<\/span>/mU", "", $elm);
                            }, $data["ulb_translation"]["l2"]);

                            $data["ulb_translation"]["l3"] = array_map(function ($elm) {
                                return preg_replace("/<span.*<\/span>/mU", "", $elm);
                            }, $data["ulb_translation"]["l3"]);

                            // Get Notes L2 translation
                            $translationData = $this->translationModel->getEventTranslationByEventID(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;

                            $chapters = $this->eventModel->getChapters(
                                $data["event"][0]->eventID, null,
                                $data["event"][0]->currentChapter);

                            $data["chunks"] = (array)json_decode($chapters[0]["chunks"], true);
                            $lastChunk = $data["chunks"][sizeof($data["chunks"]) - 1];
                            $data["totalVerses"] = $lastChunk[sizeof($lastChunk) - 1];

                            $data["comments"] = EventUtil::getComments(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter);

                            $data["event"][0]->checkerFName = null;
                            $data["event"][0]->checkerLName = null;

                            if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                && $peerCheck[$data["event"][0]->currentChapter]["memberID"] > 0) {
                                $member = $this->memberRepo->get($peerCheck[$data["event"][0]->currentChapter]["memberID"]);

                                if ($member) {
                                    $data["event"][0]->chkMemberID = $member->memberID;
                                    $data["event"][0]->checkerFName = $member->firstName;
                                    $data["event"][0]->checkerLName = $member->lastName;
                                }
                            }
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["done"] == 1) {

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $currentChapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $currentChapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    $postdata = [
                                        "step" => EventCheckSteps::PEER_EDIT_L3
                                    ];
                                    $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                    Url::redirect('events/checker-tn-review/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;

                        return View::make('Events/ReviewNotes/Checker')
                            ->nest('page', 'Events/ReviewNotes/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                        break;

                    case EventCheckSteps::PEER_EDIT_L3:
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                        $data["currentChapter"] = $data["event"][0]->currentChapter;

                        // Get related ulb project
                        $ulbProject = $this->eventModel->getProject(["projects.projectID"], [
                            ["projects.glID", $data["event"][0]->glID],
                            ["projects.gwLang", $data["event"][0]->gwLang],
                            ["projects.targetLang", $data["event"][0]->targetLang],
                            ["projects.bookProject", "ulb"]
                        ]);

                        if (!empty($ulbProject))
                            $ulbEvent = $this->eventModel->getEvent(null, $ulbProject[0]->projectID, $data["event"][0]->bookCode);

                        if (empty($ulbEvent) || $ulbEvent[0]->state != EventStates::COMPLETE)
                            $error[] = __("l2_l3_event_notexist_error");

                        if (!isset($error)) {
                            // Get ulb translation
                            $ulbTranslationData = $this->translationModel->getEventTranslationByEventID(
                                $ulbEvent[0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $ulbTranslation = ["l2" => [], "l3" => []];

                            foreach ($ulbTranslationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);

                                $ulbTranslation["l2"] += $arr[EventMembers::L2_CHECKER]["verses"];
                                $ulbTranslation["l3"] += $arr[EventMembers::L3_CHECKER]["verses"];
                            }
                            $data["ulb_translation"] = $ulbTranslation;

                            // Remove footnotes to avoid comparison errors
                            $data["ulb_translation"]["l2"] = array_map(function ($elm) {
                                return preg_replace("/<span.*<\/span>/mU", "", $elm);
                            }, $data["ulb_translation"]["l2"]);

                            $data["ulb_translation"]["l3"] = array_map(function ($elm) {
                                return preg_replace("/<span.*<\/span>/mU", "", $elm);
                            }, $data["ulb_translation"]["l3"]);

                            // Get Notes L2 translation
                            $translationData = $this->translationModel->getEventTranslationByEventID(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;

                            $chapters = $this->eventModel->getChapters(
                                $data["event"][0]->eventID, null,
                                $data["event"][0]->currentChapter);

                            $data["chunks"] = (array)json_decode($chapters[0]["chunks"], true);
                            $lastChunk = $data["chunks"][sizeof($data["chunks"]) - 1];
                            $data["totalVerses"] = $lastChunk[sizeof($lastChunk) - 1];

                            $data["comments"] = EventUtil::getComments(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter);

                            $data["event"][0]->checkerFName = null;
                            $data["event"][0]->checkerLName = null;

                            if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                && $peerCheck[$data["event"][0]->currentChapter]["memberID"] > 0) {
                                $member = $this->memberRepo->get($peerCheck[$data["event"][0]->currentChapter]["memberID"]);
                                if ($member) {
                                    $data["event"][0]->chkMemberID = $member->memberID;
                                    $data["event"][0]->checkerFName = $member->firstName;
                                    $data["event"][0]->checkerLName = $member->lastName;
                                }
                            }
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["done"] == 2) {

                                    // Update L3 if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::L3_CHECKER]["verses"])) {
                                            $tr[EventMembers::L3_CHECKER]["verses"] = $tr[EventMembers::CHECKER]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }

                                    $chapters = [];
                                    for ($i = 0; $i <= $data["event"][0]->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["l3checked"] = $chapter["l3checked"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $currentChapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $currentChapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    $chapters[$data["event"][0]->currentChapter]["l3checked"] = true;
                                    $this->eventModel->updateChapter(["l3checked" => true], [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data["event"][0]->currentChapter]);

                                    // Check if whole scripture is finished
                                    if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum + 1, false, 3)) {
                                        $this->eventModel->updateEvent([
                                            "state" => EventStates::COMPLETE,
                                            "dateTo" => date("Y-m-d H:i:s", time())],
                                            ["eventID" => $data["event"][0]->eventID]);

                                        $event = $this->eventRepo->get($data["event"][0]->eventID);
                                        $this->sendBookCompletedNotif($event, 3);
                                    }

                                    // Check if the member has another chapter to check
                                    // then redirect to preparation page
                                    $nextChapter = -1;
                                    $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"), "l3");

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => -1
                                    ];

                                    if ($nextChapter > -1) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                    if ($nextChapter > -1)
                                        Url::redirect('events/checker-tn-review/' . $data["event"][0]->eventID);
                                    else
                                        Url::redirect('events/');
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/ReviewNotes/Checker')
                            ->nest('page', 'Events/ReviewNotes/PeerEdit')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                        break;

                    case EventSteps::FINISHED:
                        $data["success"] = __("you_event_finished_success");

                        return View::make('Events/ReviewNotes/Checker')
                            ->nest('page', 'Events/ReviewNotes/Finished')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/ReviewNotes/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/ReviewNotes/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerNotesReviewPeer($eventID, $memberID, $chapter)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEventsForCheckerL3(
            Session::get("memberID"), $eventID, $memberID, $chapter);
        $data["isChecker"] = true;

        if (!empty($data["event"])) {
            if (Session::get("memberID") == $data["event"][0]->memberID) {
                Url::redirect('events/');
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L3_CHECK || $data["event"][0]->state == EventStates::COMPLETE) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PEER_REVIEW_L3:
                    case EventCheckSteps::PEER_EDIT_L3:

                        $data["currentChapter"] = $data["event"][0]->currentChapter;

                        // Get related ulb project
                        $ulbProject = $this->eventModel->getProject(["projects.projectID"], [
                            ["projects.glID", $data["event"][0]->glID],
                            ["projects.gwLang", $data["event"][0]->gwLang],
                            ["projects.targetLang", $data["event"][0]->targetLang],
                            ["projects.bookProject", "ulb"]
                        ]);

                        if (!empty($ulbProject))
                            $ulbEvent = $this->eventModel->getEvent(null, $ulbProject[0]->projectID, $data["event"][0]->bookCode);

                        if (empty($ulbEvent) || $ulbEvent[0]->state != EventStates::COMPLETE)
                            $error[] = __("l2_l3_event_notexist_error");

                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!isset($error)) {
                            // Get ulb translation
                            $ulbTranslationData = $this->translationModel->getEventTranslationByEventID(
                                $ulbEvent[0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $ulbTranslation = ["l2" => [], "l3" => []];

                            foreach ($ulbTranslationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);

                                $ulbTranslation["l2"] += $arr[EventMembers::L2_CHECKER]["verses"];
                                $ulbTranslation["l3"] += $arr[EventMembers::L3_CHECKER]["verses"];
                            }
                            $data["ulb_translation"] = $ulbTranslation;

                            // Remove footnotes to avoid comparison errors
                            $data["ulb_translation"]["l2"] = array_map(function ($elm) {
                                return preg_replace("/<span.*<\/span>/mU", "", $elm);
                            }, $data["ulb_translation"]["l2"]);

                            $data["ulb_translation"]["l3"] = array_map(function ($elm) {
                                return preg_replace("/<span.*<\/span>/mU", "", $elm);
                            }, $data["ulb_translation"]["l3"]);

                            // Get Notes L2 translation
                            $translationData = $this->translationModel->getEventTranslationByEventID(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter
                            );
                            $translation = array();

                            foreach ($translationData as $tv) {
                                $arr = (array)json_decode($tv->translatedVerses, true);
                                $arr["tID"] = $tv->tID;
                                $translation[] = $arr;
                            }
                            $data["translation"] = $translation;

                            $chapters = $this->eventModel->getChapters(
                                $data["event"][0]->eventID, null,
                                $data["event"][0]->currentChapter);

                            $data["chunks"] = (array)json_decode($chapters[0]["chunks"], true);
                            $lastChunk = $data["chunks"][sizeof($data["chunks"]) - 1];
                            $data["totalVerses"] = $lastChunk[sizeof($lastChunk) - 1];

                            $data["comments"] = EventUtil::getComments(
                                $data["event"][0]->eventID,
                                $data["event"][0]->currentChapter);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)) {
                                    if ($data["event"][0]->step == $data["event"][0]->peerStep) {
                                        if ($peerCheck[$data["event"][0]->currentChapter]["done"] == 0)
                                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                        else
                                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                        $this->eventModel->updateL3Checker([
                                                "peerCheck" => json_encode($peerCheck)
                                            ]
                                            , ["l3chID" => $data["event"][0]->l3chID]);

                                        $response["success"] = true;
                                    } else {
                                        $error[] = __("peer_checker_not_ready_error");
                                        $response["errors"] = $error;
                                    }
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        $data["next_step"] = $data["event"][0]->step == EventCheckSteps::PEER_REVIEW_L3
                            ? EventCheckSteps::PEER_EDIT_L3
                            : "continue_alt";

                        return View::make('Events/ReviewNotes/Checker')
                            ->nest('page', 'Events/ReviewNotes/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/ReviewNotes/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/ReviewNotes/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerSunReview($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isChecker"] = false;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getMemberEventsForCheckerL3(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if ($data["event"][0]->bookProject != "sun") {
                if (in_array($data["event"][0]->bookProject, ["udb", "ulb"]))
                    Url::redirect("events/checker-review/" . $eventID);
                else
                    Url::redirect("events/checker-" . $data["event"][0]->bookProject . "-review/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if (($data["event"][0]->state == EventStates::L3_CHECK
                || $data["event"][0]->state == EventStates::COMPLETE)) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-sun-review/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                $currentStep = $data["event"][0]->step;
                $currentChapter = $data["event"][0]->currentChapter;

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PRAY:

                        $data["currentChapter"] = $data["event"][0]->currentChapter;
                        if ($data["event"][0]->currentChapter == 0) {
                            $nextChapter = $this->eventModel->getNextChapter(
                                $data["event"][0]->eventID,
                                $data["event"][0]->memberID,
                                "l3");
                            $data["currentChapter"] = $nextChapter[0]->chapter;
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $peerCheck[$data["currentChapter"]] = [
                                    "memberID" => 0,
                                    "done" => 0
                                ];

                                $postdata = [
                                    "step" => EventCheckSteps::PEER_REVIEW_L3,
                                    "currentChapter" => $data["currentChapter"],
                                    "peerCheck" => json_encode($peerCheck)
                                ];
                                $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                Url::redirect('events/checker-sun-review/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->peerCheck == "";
                        $data["next_step"] = EventCheckSteps::PEER_REVIEW_L3;

                        return View::make('Events/ReviewSun/Checker')
                            ->nest('page', 'Events/ReviewSun/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::PEER_REVIEW_L3:
                        $sourceText = $this->getScriptureSourceText($data);
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $data["event"][0]->chkMemberID = null;
                                $data["event"][0]->checkerFName = null;
                                $data["event"][0]->checkerLName = null;

                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["memberID"] > 0) {
                                    $member = $this->memberRepo->get($peerCheck[$data["event"][0]->currentChapter]["memberID"]);
                                    if ($member) {
                                        $data["event"][0]->chkMemberID = $member->memberID;
                                        $data["event"][0]->checkerFName = $member->firstName;
                                        $data["event"][0]->checkerLName = $member->lastName;
                                    }
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL3Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l3chID" => $data["event"][0]->l3chID
                            ]);
                            Url::redirect('events/checker-sun-review/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["done"] == 1) {

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $currentChapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $currentChapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    $postdata = [
                                        "step" => EventCheckSteps::PEER_EDIT_L3
                                    ];
                                    $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);
                                    Url::redirect('events/checker-sun-review/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;

                        return View::make('Events/ReviewSun/Checker')
                            ->nest('page', 'Events/ReviewSun/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                        break;

                    case EventCheckSteps::PEER_EDIT_L3:
                        $sourceText = $this->getScriptureSourceText($data);
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $data["event"][0]->chkMemberID = null;
                                $data["event"][0]->checkerFName = null;
                                $data["event"][0]->checkerLName = null;

                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["memberID"] > 0) {
                                    $member = $this->memberRepo->get($peerCheck[$data["event"][0]->currentChapter]["memberID"]);
                                    if ($member) {
                                        $data["event"][0]->chkMemberID = $member->memberID;
                                        $data["event"][0]->checkerFName = $member->firstName;
                                        $data["event"][0]->checkerLName = $member->lastName;
                                    }
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL3Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l3chID" => $data["event"][0]->l3chID
                            ]);
                            Url::redirect('events/checker-sun-review/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["done"] == 2) {
                                    // Update L3 if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::L3_CHECKER]["verses"])) {
                                            $tr[EventMembers::L3_CHECKER]["verses"] = $tr[EventMembers::TRANSLATOR]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }

                                    $chapters = [];
                                    for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $currentChapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $currentChapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["l3checked"] = $chapter["l3checked"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$data["event"][0]->currentChapter]["l3checked"] = true;
                                    $this->eventModel->updateChapter(["l3checked" => true], [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data["event"][0]->currentChapter]);

                                    // Check if whole scripture is finished
                                    if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum, false, 3)) {
                                        $this->eventModel->updateEvent([
                                            "state" => EventStates::COMPLETE,
                                            "dateTo" => date("Y-m-d H:i:s", time())],
                                            ["eventID" => $data["event"][0]->eventID]);

                                        $event = $this->eventRepo->get($data["event"][0]->eventID);
                                        $this->sendBookCompletedNotif($event, 3);
                                    }

                                    // Check if the member has another chapter to check
                                    // then redirect to preparation page
                                    $nextChapter = 0;
                                    $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"), "l3");

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => 0
                                    ];

                                    if ($nextChapter > 0) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                    if ($nextChapter > 0)
                                        Url::redirect('events/checker-sun-review/' . $data["event"][0]->eventID);
                                    else
                                        Url::redirect('events/');
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/ReviewSun/Checker')
                            ->nest('page', 'Events/ReviewSun/PeerEdit')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                        break;

                    case EventSteps::FINISHED:
                        $data["success"] = __("you_event_finished_success");

                        return View::make('Events/ReviewSun/Checker')
                            ->nest('page', 'Events/ReviewSun/Finished')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/ReviewSun/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/ReviewSun/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerSunReviewPeer($eventID, $memberID, $chapter)
    {
        $isXhr = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isXhr = true;
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEventsForCheckerL3(
            Session::get("memberID"), $eventID, $memberID, $chapter);
        $data["isChecker"] = true;

        if (!empty($data["event"])) {
            if (Session::get("memberID") == $data["event"][0]->memberID) {
                Url::redirect('events/');
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L3_CHECK || $data["event"][0]->state == EventStates::COMPLETE) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PEER_REVIEW_L3:
                    case EventCheckSteps::PEER_EDIT_L3:
                        $sourceText = $this->getScriptureSourceText($data);
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $error[] = $sourceText["error"];
                            $data["error"] = $sourceText["error"];
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)) {
                                    if ($data["event"][0]->step == $data["event"][0]->peerStep) {
                                        if ($peerCheck[$data["event"][0]->currentChapter]["done"] == 0)
                                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                        else
                                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                        $this->eventModel->updateL3Checker([
                                                "peerCheck" => json_encode($peerCheck)
                                            ]
                                            , ["l3chID" => $data["event"][0]->l3chID]);

                                        $response["success"] = true;
                                    } else {
                                        $error[] = __("peer_checker_not_ready_error");
                                        $response["errors"] = $error;
                                    }
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                        $data["next_step"] = $data["event"][0]->step == EventCheckSteps::PEER_REVIEW_L3
                            ? EventCheckSteps::PEER_EDIT_L3
                            : "continue_alt";

                        return View::make('Events/ReviewSun/Checker')
                            ->nest('page', 'Events/ReviewSun/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/ReviewSun/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/ReviewSun/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerReview($eventID)
    {
        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["isChecker"] = false;
        $data["isCheckerPage"] = true;
        $data["event"] = $this->eventModel->getMemberEventsForCheckerL3(Session::get("memberID"), $eventID);

        if (!empty($data["event"])) {
            if (!in_array($data["event"][0]->bookProject, ["ulb", "udb"])) {
                Url::redirect("events/checker-" . $data["event"][0]->bookProject . "-review/" . $eventID);
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if (($data["event"][0]->state == EventStates::L3_CHECK
                || $data["event"][0]->state == EventStates::COMPLETE)) {
                if ($data["event"][0]->step == EventSteps::NONE)
                    Url::redirect("events/information-review/" . $eventID);

                $data["turn"] = EventUtil::makeTurnCredentials();

                $currentStep = $data["event"][0]->step;
                $currentChapter = $data["event"][0]->currentChapter;

                switch ($currentStep) {
                    case EventCheckSteps::PRAY:

                        $data["currentChapter"] = $data["event"][0]->currentChapter;
                        if ($data["event"][0]->currentChapter == 0) {
                            $nextChapter = $this->eventModel->getNextChapter(
                                $data["event"][0]->eventID,
                                $data["event"][0]->memberID,
                                "l3");
                            $data["currentChapter"] = $nextChapter[0]->chapter;
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);
                                $peerCheck[$data["currentChapter"]] = [
                                    "memberID" => 0,
                                    "done" => 0
                                ];

                                $postdata = [
                                    "step" => EventCheckSteps::PEER_REVIEW_L3,
                                    "currentChapter" => $data["currentChapter"],
                                    "peerCheck" => json_encode($peerCheck)
                                ];
                                $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                Url::redirect('events/checker-review/' . $data["event"][0]->eventID);
                            }
                        }

                        // Check if translator just started translating of this book
                        $data["event"][0]->justStarted = $data["event"][0]->peerCheck == "";
                        $data["next_step"] = EventCheckSteps::PEER_REVIEW_L3;

                        return View::make('Events/Review/Checker')
                            ->nest('page', 'Events/Review/Pray')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                    case EventCheckSteps::PEER_REVIEW_L3:
                        $sourceText = $this->getScriptureSourceText($data);
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $data["event"][0]->chkMemberID = null;
                                $data["event"][0]->checkerFName = null;
                                $data["event"][0]->checkerLName = null;

                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["memberID"] > 0) {
                                    $member = $this->memberRepo->get($peerCheck[$data["event"][0]->currentChapter]["memberID"]);
                                    if ($member) {
                                        $data["event"][0]->chkMemberID = $member->memberID;
                                        $data["event"][0]->checkerFName = $member->firstName;
                                        $data["event"][0]->checkerLName = $member->lastName;
                                    }
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL3Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l3chID" => $data["event"][0]->l3chID
                            ]);
                            Url::redirect('events/checker-review/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["done"] == 1) {
                                    $postdata = [
                                        "step" => EventCheckSteps::PEER_EDIT_L3
                                    ];
                                    $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $currentChapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $currentChapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    Url::redirect('events/checker-review/' . $data["event"][0]->eventID);
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;

                        return View::make('Events/Review/Checker')
                            ->nest('page', 'Events/Review/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                        break;

                    case EventCheckSteps::PEER_EDIT_L3:
                        $sourceText = $this->getScriptureSourceText($data);
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);

                                $data["event"][0]->chkMemberID = null;
                                $data["event"][0]->checkerFName = null;
                                $data["event"][0]->checkerLName = null;

                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["memberID"] > 0) {
                                    $member = $this->memberRepo->get($peerCheck[$data["event"][0]->currentChapter]["memberID"]);
                                    if ($member) {
                                        $data["event"][0]->chkMemberID = $member->memberID;
                                        $data["event"][0]->checkerFName = $member->firstName;
                                        $data["event"][0]->checkerLName = $member->lastName;
                                    }
                                }
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $this->eventModel->updateL3Checker([
                                "step" => EventCheckSteps::NONE
                            ], [
                                "l3chID" => $data["event"][0]->l3chID
                            ]);
                            Url::redirect('events/checker-review/' . $data["event"][0]->eventID);
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)
                                    && $peerCheck[$data["event"][0]->currentChapter]["done"] == 2) {
                                    // Update L3 if it's empty
                                    foreach ($translation as $tr) {
                                        if (empty($tr[EventMembers::L3_CHECKER]["verses"])) {
                                            $tr[EventMembers::L3_CHECKER]["verses"] = $tr[EventMembers::L2_CHECKER]["verses"];
                                            $tID = $tr["tID"];
                                            unset($tr["tID"]);
                                            $this->translationModel->updateTranslation(
                                                ["translatedVerses" => json_encode($tr)],
                                                ["tID" => $tID]
                                            );
                                        }
                                    }

                                    $chapters = [];
                                    for ($i = 1; $i <= $data["event"][0]->chaptersNum; $i++) {
                                        $data["chapters"][$i] = [];
                                    }

                                    $chaptersDB = $this->eventModel->getChapters($data["event"][0]->eventID);

                                    foreach ($chaptersDB as $chapter) {
                                        $tmp["trID"] = $chapter["trID"];
                                        $tmp["memberID"] = $chapter["memberID"];
                                        $tmp["chunks"] = json_decode($chapter["chunks"], true);
                                        $tmp["l3checked"] = $chapter["l3checked"];

                                        $chapters[$chapter["chapter"]] = $tmp;
                                    }

                                    $chapters[$data["event"][0]->currentChapter]["l3checked"] = true;
                                    $this->eventModel->updateChapter(["l3checked" => true], [
                                        "eventID" => $data["event"][0]->eventID,
                                        "chapter" => $data["event"][0]->currentChapter]);

                                    // Check if whole scripture is finished
                                    if ($this->checkBookFinished($chapters, $data["event"][0]->chaptersNum, false, 3)) {
                                        $this->eventModel->updateEvent([
                                            "state" => EventStates::COMPLETE,
                                            "dateTo" => date("Y-m-d H:i:s", time())],
                                            ["eventID" => $data["event"][0]->eventID]);

                                        $event = $this->eventRepo->get($data["event"][0]->eventID);
                                        $this->sendBookCompletedNotif($event, 3);
                                    }

                                    // Check if the member has another chapter to check
                                    // then redirect to preparation page
                                    $nextChapter = 0;
                                    $nextChapterDB = $this->eventModel->getNextChapter($data["event"][0]->eventID, Session::get("memberID"), "l3");

                                    if (!empty($nextChapterDB))
                                        $nextChapter = $nextChapterDB[0]->chapter;

                                    $postdata = [
                                        "step" => EventSteps::NONE,
                                        "currentChapter" => 0
                                    ];

                                    if ($nextChapter > 0) {
                                        $postdata["step"] = EventSteps::PRAY;
                                        $postdata["currentChapter"] = $nextChapter;
                                    }

                                    $this->eventModel->updateL3Checker($postdata, ["l3chID" => $data["event"][0]->l3chID]);

                                    // Cleanup chapter notifications for this step
                                    $myNotifications = $this->eventRepo->getToNotifications($this->member->memberID)
                                        ->filter(function($item) use ($eventID, $currentChapter, $currentStep) {
                                            return $item->eventID == $eventID
                                                && $item->currentChapter == $currentChapter
                                                && $item->step == $currentStep;
                                        });

                                    $myNotifications->each(function($item) {
                                        $item->delete();
                                    });

                                    if ($nextChapter > 0)
                                        Url::redirect('events/checker-review/' . $data["event"][0]->eventID);
                                    else
                                        Url::redirect('events/');
                                } else {
                                    $error[] = __("checker_not_ready_error");
                                }
                            }
                        }

                        $data["next_step"] = "continue_alt";

                        return View::make('Events/Review/Checker')
                            ->nest('page', 'Events/Review/PeerEdit')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);

                        break;

                    case EventSteps::FINISHED:
                        $data["success"] = __("you_event_finished_success");

                        return View::make('Events/Review/Checker')
                            ->nest('page', 'Events/Review/Finished')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Review/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Review/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function checkerReviewPeer($eventID, $memberID, $chapter)
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response["success"] = false;
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;
        $data["newNewsCount"] = $this->newNewsCount;
        $data["event"] = $this->eventModel->getMemberEventsForCheckerL3(
            Session::get("memberID"), $eventID, $memberID, $chapter);
        $data["isChecker"] = true;

        if (!empty($data["event"])) {
            if (Session::get("memberID") == $data["event"][0]->memberID) {
                Url::redirect('events/');
            }

            $title = $data["event"][0]->name
                . " " . ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : "")
                . " - " . $data["event"][0]->tLang
                . " - " . __($data["event"][0]->bookProject);

            if ($data["event"][0]->state == EventStates::L3_CHECK || $data["event"][0]->state == EventStates::COMPLETE) {
                $data["turn"] = EventUtil::makeTurnCredentials();

                $chapters = $this->eventModel->getChapters($eventID, null, $chapter);
                $data["event"][0]->chunks = [];
                if (!empty($chapters)) {
                    $data["event"][0]->chunks = $chapters[0]["chunks"];
                }

                switch ($data["event"][0]->step) {
                    case EventCheckSteps::PEER_REVIEW_L3:
                    case EventCheckSteps::PEER_EDIT_L3:
                        $sourceText = $this->getScriptureSourceText($data);
                        $peerCheck = (array)json_decode($data["event"][0]->peerCheck, true);

                        if (!empty($sourceText)) {
                            if (!array_key_exists("error", $sourceText)) {
                                $data = $sourceText;

                                $translationData = $this->translationModel->getEventTranslationByEventID(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter
                                );
                                $translation = array();

                                foreach ($translationData as $tv) {
                                    $arr = (array)json_decode($tv->translatedVerses, true);
                                    $arr["tID"] = $tv->tID;
                                    $translation[] = $arr;
                                }
                                $data["translation"] = $translation;

                                $data["comments"] = EventUtil::getComments(
                                    $data["event"][0]->eventID,
                                    $data["event"][0]->currentChapter);
                            } else {
                                $error[] = $sourceText["error"];
                                $data["error"] = $sourceText["error"];
                            }
                        } else {
                            $error[] = $sourceText["error"];
                            $data["error"] = $sourceText["error"];
                        }

                        if (isset($_POST) && !empty($_POST)) {
                            if (isset($_POST["confirm_step"])) {
                                if (array_key_exists($data["event"][0]->currentChapter, $peerCheck)) {
                                    if ($data["event"][0]->step == $data["event"][0]->peerStep) {
                                        if ($peerCheck[$data["event"][0]->currentChapter]["done"] == 0)
                                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 1;
                                        else
                                            $peerCheck[$data["event"][0]->currentChapter]["done"] = 2;

                                        $this->eventModel->updateL3Checker([
                                                "peerCheck" => json_encode($peerCheck)
                                            ]
                                            , ["l3chID" => $data["event"][0]->l3chID]);

                                        $response["success"] = true;
                                    } else {
                                        $error[] = __("peer_checker_not_ready_error");
                                        $response["errors"] = $error;
                                    }
                                    echo json_encode($response);
                                    exit;
                                }
                            }
                        }

                    $data["next_step"] = $data["event"][0]->step == EventCheckSteps::PEER_REVIEW_L3
                        ? EventCheckSteps::PEER_EDIT_L3
                        : "continue_alt";

                        return View::make('Events/Review/Checker')
                            ->nest('page', 'Events/Review/PeerReview')
                            ->shares("title", $title)
                            ->shares("data", $data)
                            ->shares("error", @$error);
                        break;
                }
            } else {
                $data["error"] = true;
                $error[] = __("wrong_event_state_error");

                return View::make('Events/Review/Checker')
                    ->shares("title", $title)
                    ->shares("data", $data)
                    ->shares("error", @$error);
            }
        } else {
            $error[] = __("not_in_event_error");
            $title = "Error";

            return View::make('Events/Review/Checker')
                ->shares("title", $title)
                ->shares("data", $data)
                ->shares("error", @$error);
        }
    }

    public function news()
    {
        if (!$this->member) Url::redirect("members/login");

        $data["menu"] = 6;
        $data["notifications"] = $this->renderedNotifications;
        $data["news"] = $this->news;
        $data["newNewsCount"] = 0;

        return View::make('Events/News')
            ->shares("title", __("news_title"))
            ->shares("data", $data);
    }

    public function faqs()
    {
        $this->newsModel = new NewsModel();
        $data["menu"] = 0;
        $data["faqs"] = $this->newsModel->getFaqs();

        return View::make('Events/Faq')
            ->shares("title", __("faq_title"))
            ->shares("data", $data);
    }


    private function saveChunk($post, $event, $memberType) {
        $response = array("success" => false);
        $mode = $event->bookProject;

        if (isset($post["draft"]) && Tools::trim(Tools::strip_tags($post["draft"])) != "") {
            $chunks = json_decode($event->chunks, true);
            $chunk = $chunks[$event->currentChunk];

            $post["draft"] = preg_replace("/[\\r\\n]/", " ", $post["draft"]);
            $post["draft"] = Tools::html_entity_decode($post["draft"]);

            if (in_array($mode, ["tn"])) {
                $converter = new Converter;
                $converter->setKeepHTML(false);
                $post["draft"] = $converter->parseString($post["draft"]);
            } else {
                $post["draft"] = Tools::htmlentities($post["draft"]);
            }

            $translationData = $this->translationModel->getEventTranslationByEventID(
                $event->eventID,
                $event->currentChapter,
                $event->currentChunk
            );

            $shouldUpdate = false;

            if (!empty($translationData)) {
                if ($translationData[0]->chapter == $event->currentChapter
                    && $translationData[0]->chunk == $event->currentChunk) {
                    $translationVerses = json_decode($translationData[0]->translatedVerses, true);
                    $shouldUpdate = true;
                }
            }

            if (!$shouldUpdate) {
                $trArr = [];
                if (in_array($mode, ["tn"])) {
                    $trArr["verses"] = trim($post["draft"]);
                } elseif ($mode == "sun") {
                    $trArr["words"] = trim($post["draft"]);
                    $trArr["symbols"] = "";
                    $trArr["bt"] = "";
                    $trArr["verses"] = [];
                } elseif (in_array($mode, ["obs","bc","bca"])) {
                    $trArr["verses"]["text"] = trim($post["draft"]);
                    $trArr["verses"]["meta"] = trim($post["meta"]);
                    $trArr["verses"]["type"] = trim($post["type"]);
                } else {
                    $trArr["blind"] = trim($post["draft"]);
                    $trArr["verses"] = [];
                }

                $translationVerses = array(
                    EventMembers::TRANSLATOR => $trArr,
                    EventMembers::L2_CHECKER => array(
                        "verses" => []
                    ),
                    EventMembers::L3_CHECKER => array(
                        "verses" => []
                    ),
                );

                if (in_array($mode, ["tn","obs","bc","bca"]))
                    $translationVerses[EventMembers::CHECKER] = [
                        "verses" => []
                    ];

                $encoded = json_encode($translationVerses);
                $json_error = json_last_error();
                if ($json_error === JSON_ERROR_NONE) {
                    $trData = array(
                        "projectID" => $event->projectID,
                        "eventID" => $event->eventID,
                        "trID" => $event->trID,
                        "targetLang" => $event->targetLang,
                        "bookProject" => $event->bookProject,
                        "sort" => $event->sort,
                        "bookCode" => $event->bookCode,
                        "chapter" => $event->currentChapter,
                        "chunk" => $event->currentChunk,
                        "firstvs" => $chunk[0],
                        "translatedVerses" => $encoded,
                        "dateCreate" => date('Y-m-d H:i:s')
                    );

                    $tID = $this->translationModel->createTranslation($trData);

                    if (is_numeric($tID)) {
                        $response["chapter"] = $event->currentChapter;
                        $response["chunk"] = $event->currentChunk;
                        $response["success"] = true;
                    }
                } else {
                    $response["errorType"] = "json";
                    $response["error"] = "Json error: " . $json_error;
                }
            } else {
                if (in_array($mode, ["tn"])) {
                    $translationVerses[$memberType]["verses"] = trim($post["draft"]);
                } elseif ($mode == "sun") {
                    if ($event->step == EventSteps::SYMBOL_DRAFT)
                        $translationVerses[$memberType]["symbols"] = trim($post["draft"]);
                    else
                        $translationVerses[$memberType]["words"] = trim($post["draft"]);
                } elseif (in_array($mode, ["obs","bc","bca"])) {
                    $translationVerses[$memberType]["verses"]["text"] = trim($post["draft"]);
                    $translationVerses[$memberType]["verses"]["meta"] = trim($post["meta"]);
                    $translationVerses[$memberType]["verses"]["type"] = trim($post["type"]);
                } else {
                    $translationVerses[$memberType]["blind"] = trim($post["draft"]);
                }

                $encoded = json_encode($translationVerses);
                $json_error = json_last_error();
                if ($json_error === JSON_ERROR_NONE) {
                    $trData = array(
                        "translatedVerses" => $encoded,
                    );

                    $this->translationModel->updateTranslation($trData, array("tID" => $translationData[0]->tID));
                    $response["chapter"] = $event->currentChapter;
                    $response["chunk"] = $event->currentChunk;
                    $response["success"] = true;
                } else {
                    $response["errorType"] = "json";
                    $response["error"] = "Json error: " . $json_error;
                }
            }
        }

        return $response;
    }

    private function saveChunks($post, $event, $memberType) {
        $response = ["success" => false];

        if (isset($post["chunks"]) && is_array($post["chunks"]) && !empty($post["chunks"])) {
            if ($event->step == EventSteps::PEER_REVIEW
                || $event->step == EventSteps::KEYWORD_CHECK
                || $event->step == EventSteps::CONTENT_REVIEW) {
                if ($event->checkDone) {
                    $response["errorType"] = "checkDone";
                    $response["error"] = __("not_possible_to_save_error");
                    echo json_encode($response);
                    exit;
                }
            }

            $translationData = $this->translationModel->getEventTranslationByEventID(
                $event->eventID,
                $event->currentChapter
            );

            if ($event->step == EventSteps::MULTI_DRAFT && empty($translationData)) {
                $translationVerses = [
                    EventMembers::TRANSLATOR => ["verses" => ""],
                    EventMembers::CHECKER => ["verses" => ""],
                    EventMembers::L2_CHECKER => ["verses" => []],
                    EventMembers::L3_CHECKER => ["verses" => []],
                ];
                $encoded = json_encode($translationVerses);
                $chunks = json_decode($event->chunks, true);

                foreach ($post["chunks"] as $key => $chunk) {
                    $chunk = $chunks[$key];
                    $trData = array(
                        "projectID" => $event->projectID,
                        "eventID" => $event->eventID,
                        "trID" => $event->trID,
                        "targetLang" => $event->targetLang,
                        "bookProject" => $event->bookProject,
                        "sort" => $event->sort,
                        "bookCode" => $event->bookCode,
                        "chapter" => $event->currentChapter,
                        "chunk" => $key,
                        "firstvs" => $event->bookProject == "tw" ? $key : $chunk[0],
                        "translatedVerses" => $encoded,
                        "dateCreate" => date('Y-m-d H:i:s')
                    );

                    $this->translationModel->createTranslation($trData);
                }

                $translationData = $this->translationModel->getEventTranslationByEventID(
                    $event[0]->eventID,
                    $event[0]->currentChapter
                );
            }

            $translation = [];

            foreach ($translationData as $tv) {
                $arr = json_decode($tv->translatedVerses, true);
                $arr["tID"] = $tv->tID;
                $translation[] = $arr;
            }

            if (!empty($translation)) {
                // Clean empty spaces
                $post["chunks"] = array_map(function ($elm) {
                    return Tools::trim($elm);
                }, $post["chunks"]);

                // filter out empty chunks
                $post["chunks"] = array_filter($post["chunks"], function ($v) {
                    return !empty(Tools::trim(Tools::strip_tags($v)));
                });

                $section = "blind";
                $symbols = [];
                if ($event->bookProject == "sun") {
                    if ($event->step == EventSteps::SELF_CHECK)
                        $section = "bt";
                    elseif ($event->step == EventSteps::CONTENT_REVIEW
                        || $event->step == EventSteps::THEO_CHECK
                        || $event->sourceBible == "odb")
                        $section = "symbols";

                    if (isset($post["symbols"]) && is_array($post["symbols"]) && !empty($post["symbols"])) {
                        $post["symbols"] = array_map(function ($elm) {
                            return Tools::trim($elm);
                        }, $post["symbols"]);
                        $post["symbols"] = array_filter($post["symbols"], function ($v) {
                            return !empty(Tools::trim(strip_tags($v)));
                        });

                        $symbols = $post["symbols"];
                    }
                } elseif (in_array($event->bookProject, ["tn","tq","tw","rad","obs","bc","bca"])) {
                    $section = "verses";
                }

                $updated = 0;
                foreach ($translation as $key => $chunk) {
                    if (!isset($post["chunks"][$key])) continue;

                    $post["chunks"][$key] = Tools::html_entity_decode($post["chunks"][$key]);

                    if (in_array($event->bookProject, ["tn","tq","tw"])) {
                        $converter = new Converter;
                        $converter->setKeepHTML(false);
                        $post["chunks"][$key] = $converter->parseString($post["chunks"][$key]);

                        if (!array_key_exists(EventMembers::CHECKER, $chunk)) {
                            $chunk[EventMembers::CHECKER] = ["verses" => ""];
                        }
                    } else {
                        $post["chunks"][$key] = Tools::htmlentities($post["chunks"][$key]);
                    }

                    $shouldUpdate = false;
                    if ($chunk[$memberType][$section] != $post["chunks"][$key])
                        $shouldUpdate = true;

                    if ($event->bookProject == "sun" && !empty($symbols)) {
                        if (!isset($symbols[$key])) continue;

                        if ($chunk[$memberType]["symbols"] != $symbols[$key])
                            $shouldUpdate = true;

                        $symbols[$key] = htmlentities(html_entity_decode($symbols[$key]));
                        $translation[$key][$memberType]["symbols"] = $symbols[$key];
                    }

                    $translation[$key][$memberType][$section] = $post["chunks"][$key];

                    if ($shouldUpdate) {
                        $tID = $translation[$key]["tID"];
                        unset($translation[$key]["tID"]);

                        $encoded = json_encode($translation[$key]);
                        $json_error = json_last_error();
                        if ($json_error === JSON_ERROR_NONE) {
                            $trData = array(
                                "translatedVerses" => $encoded
                            );
                            $this->translationModel->updateTranslation(
                                $trData,
                                ["tID" => $tID]
                            );
                            $updated++;
                        }
                    }
                }

                if ($updated)
                    $response["success"] = true;
                else {
                    $response["errorType"] = "noChange";
                    $response["error"] = "no_change";
                }
            }
        }

        return $response;
    }

    private function saveVerses($post, $event, $memberType) {
        $response = ["success" => false];

        if (isset($post["chunks"]) && is_array($post["chunks"]) && !empty($post["chunks"])) {
            if ($event->step == EventCheckSteps::PEER_REVIEW
                || $event->step == EventCheckSteps::KEYWORD_CHECK
                || $event->step == EventCheckSteps::CONTENT_REVIEW) {
                if ($event->checkDone) {
                    $response["errorType"] = "checkDone";
                    $response["error"] = __("not_possible_to_save_error");
                    echo json_encode($response);
                    exit;
                }
            } elseif ($event->step == EventCheckSteps::PEER_EDIT_L3) {
                $peerCheck = (array)json_decode($event->peerCheck, true);
                if (array_key_exists($event->currentChapter, $peerCheck) &&
                    $peerCheck[$event->currentChapter]["done"] == 2) {
                    $response["errorType"] = "checkDone";
                    $response["error"] = __("not_possible_to_save_error");
                    echo json_encode($response);
                    exit;
                }
            }
        }

        $translationData = $this->translationModel->getEventTranslationByEventID(
            $event->eventID,
            $event->currentChapter);
        $translation = array();

        foreach ($translationData as $tv) {
            $arr = json_decode($tv->translatedVerses, true);
            $arr["tID"] = $tv->tID;
            $translation[] = $arr;
        }

        if (!empty($translation)) {
            if (in_array($event->bookProject, ["tn","tq","tw"])) {
                array_walk_recursive($post["chunks"], function (&$item) {
                    $item = trim($item);
                });

                $post["chunks"] = array_map("trim", $post["chunks"]);
                $post["chunks"] = array_filter($post["chunks"], function ($v) {
                    return !empty(Tools::strip_tags($v));
                });
            } else {
                $post["chunks"] = array_filter($post["chunks"], function ($chunk) {
                    $verses = array_filter($chunk, function ($v) {
                        return !empty(Tools::strip_tags(trim($v)));
                    });
                    $isEqual = sizeof($chunk) == sizeof($verses);
                    return !empty($chunk) && $isEqual;
                });
            }

            $updated = 0;
            foreach ($translation as $key => $chunk) {
                if (!isset($post["chunks"][$key])) continue;

                $post["chunks"][$key] = Tools::html_entity_decode($post["chunks"][$key]);

                $shouldUpdate = false;

                if (in_array($event->bookProject, ["tn","tq","tw"])) {
                    $converter = new Converter;
                    $converter->setKeepHTML(false);
                    $post["chunks"][$key] = $converter->parseString($post["chunks"][$key]);

                    if ($chunk[$memberType]["verses"] != $post["chunks"][$key])
                        $shouldUpdate = true;
                } else {
                    $post["chunks"][$key] = Tools::htmlentities($post["chunks"][$key]);
                    if (is_array($post["chunks"][$key])) {
                        foreach ($post["chunks"][$key] as $verse => $vText) {
                            if (!isset($chunk[$memberType]["verses"][$verse])
                                || $chunk[$memberType]["verses"][$verse] != $vText) {
                                $shouldUpdate = true;
                            }
                        }
                    } else {
                        if ($chunk[$memberType]["verses"] != $post["chunks"][$key]) {
                            $shouldUpdate = true;
                        }
                    }
                }

                $translation[$key][$memberType]["verses"] = $post["chunks"][$key];

                if ($shouldUpdate) {
                    $tID = $translation[$key]["tID"];
                    unset($translation[$key]["tID"]);

                    $encoded = json_encode($translation[$key]);
                    $json_error = json_last_error();
                    if ($json_error === JSON_ERROR_NONE) {
                        $trData = array(
                            "translatedVerses" => $encoded
                        );
                        $this->translationModel->updateTranslation(
                            $trData,
                            ["tID" => $tID]);
                        $updated++;
                    }
                }
            }

            if ($updated)
                $response["success"] = true;
            else {
                $response["errorType"] = "noChange";
                $response["error"] = "no_change";
            }
        }

        return $response;
    }

    private function saveVersesWithMarkers($post, $event) {
        $response = ["success" => false];

        $trID = $event->trID;
        $translationData = $this->translationModel->getEventTranslation(
            $trID,
            $event->currentChapter);

        $translationVerses = array(
            EventMembers::TRANSLATOR => array(
                "blind" => "",
                "verses" => ""
            ),
            EventMembers::L2_CHECKER => array(
                "verses" => array()
            ),
            EventMembers::L3_CHECKER => array(
                "verses" => array()
            ),
        );

        $finalVerses = isset($post["verses"]) && is_array($post["verses"]) && !empty($post["verses"])
            ? $post["verses"] : [];

        if(isset($post["draft"]) && !empty($post["draft"])) {
            $verses = preg_split("/\|(\d+)\|/", $post["draft"]);
            $finalVerses = [];

            if (sizeof($verses) == 1) {
                $finalVerses[1] = $verses[0];
            } else {
                $vNumber = 1;
                unset($verses[0]);
                foreach ($verses as $verse) {
                    $finalVerses[$vNumber] = $verse;
                    $vNumber++;
                }
            }
            $finalVerses = array_map(function($item) {
                $item = preg_replace("/[\s\t\n\r]+/", " ", $item);
                return trim($item, " \t\n\r\0\x0B\xC2\xA0");
            }, $finalVerses);
        }

        if (!empty($finalVerses)) {
            // Store verses and their related ids
            $ids = [];

            foreach ($finalVerses as $verse => $text) {
                $text = strip_tags(html_entity_decode($text));

                if (empty(trim($text)) || !is_integer($verse) || $verse < 1) {
                    if (in_array($event->step, [
                        EventSteps::SELF_CHECK,
                        EventSteps::PEER_REVIEW,
                        EventSteps::FINAL_REVIEW
                    ])) {
                        $response["error"] = "empty input";
                        echo json_encode($response);
                        exit;
                    } else {
                        continue;
                    }
                }

                $updated = false;
                foreach ($translationData as $chunk) {
                    if ($chunk->firstvs == $verse) {
                        // Update verse
                        $translationVerses[EventMembers::TRANSLATOR]["verses"] = [];
                        $translationVerses[EventMembers::TRANSLATOR]["verses"][$verse] = $text;

                        $encoded = json_encode($translationVerses);
                        $json_error = json_last_error();
                        if ($json_error === JSON_ERROR_NONE) {
                            $this->translationModel->updateTranslation(
                                ["translatedVerses" => $encoded],
                                array(
                                    "trID" => $trID,
                                    "tID" => $chunk->tID));
                            $ids[$verse] = $chunk->tID;
                            $updated = true;
                        } else {
                            $response["errorType"] = "json";
                            $response["error"] = "Json error: " . $json_error;
                            echo json_encode($response);
                            exit;
                        }
                        break;
                    }
                }

                if (!$updated) {
                    // Create verse
                    $translationVerses[EventMembers::TRANSLATOR]["verses"] = [];
                    $translationVerses[EventMembers::TRANSLATOR]["verses"][$verse] = $text;

                    $encoded = json_encode($translationVerses);
                    $json_error = json_last_error();

                    if ($json_error === JSON_ERROR_NONE) {
                        $trData = array(
                            "projectID" => $event->projectID,
                            "eventID" => $event->eventID,
                            "trID" => $event->trID,
                            "targetLang" => $event->targetLang,
                            "bookProject" => $event->bookProject,
                            "sort" => $event->sort,
                            "bookCode" => $event->bookCode,
                            "chapter" => $event->currentChapter,
                            "chunk" => $verse - 1,
                            "firstvs" => $verse,
                            "translatedVerses" => $encoded,
                            "dateCreate" => date('Y-m-d H:i:s')
                        );
                        $id = $this->translationModel->createTranslation($trData);
                        if ($id)
                            $ids[$verse] = $id;
                    } else {
                        $response["errorType"] = "json";
                        $response["error"] = "Json error: " . $json_error;
                        echo json_encode($response);
                        exit;
                    }
                }
            }

            $stripped = sizeof($translationData) - sizeof($finalVerses);
            if ($stripped > 0) {
                $strippedData = array_slice($translationData, -$stripped);
                foreach ($strippedData as $verse) {
                    $this->translationModel->deleteTranslation([
                        "tID" => $verse->tID
                    ]);
                }
            }

            $response["success"] = true;
            $response["ids"] = $ids;
        }

        return $response;
    }

    public function autoSaveChunk() {
        $response = array("success" => false);
        $post = $_REQUEST;
        $eventID = $post["eventID"] ?? null;

        if ($eventID !== null) {
            $level = $post["level"] ?? "l1";
            $isCheckingStep = $post["isChecking"] ?? false;
            $checkingChapter = $post["checkingChapter"] ?? null;

            $memberType = EventMembers::TRANSLATOR;
            if ($level == "l2" || $level == "l2Continue")
                $memberType = EventMembers::L2_CHECKER;
            elseif ($level == "l3")
                $memberType = EventMembers::L3_CHECKER;

            if ($level == "l1") {
                $eventData = $this->eventModel->getMemberEvents(
                    Session::get("memberID"),
                    $eventID,
                    $checkingChapter,
                    $isCheckingStep,
                    false,
                    false
                );
            } elseif ($level == "l2") {
                $eventData = $this->eventModel->getRevisionMemberEvents(
                    Session::get("memberID"),
                    $eventID,
                    $checkingChapter,
                    $isCheckingStep,
                    false
                );
            } elseif ($level == "l2Continue") {
                $bookProject = $post["bookProject"] ?? null;
                if ($bookProject == "sun") {
                    $eventData = $this->eventModel->getMemberEventsForSunRevisionChecker(
                        Session::get("memberID"),
                        $eventID,
                        $post["memberID"],
                        $post["chapter"]
                    );
                } else {
                    $eventData = $this->eventModel->getMemberEventsForRevisionChecker(
                        Session::get("memberID"),
                        $eventID,
                        $post["memberID"],
                        $post["chapter"]
                    );
                }
            } elseif ($level == "l3") {
                $eventData = $this->eventModel->getCheckerL3Events(Session::get("memberID"), $eventID);
            } elseif ($level == "sunContinue") {
                if (isset($post["memberID"]) && isset($post["chapter"])) {
                    $eventData = $this->eventModel->getMemberEventsForSun(
                        Session::get("memberID"),
                        $eventID,
                        $post["memberID"],
                        $post["chapter"]
                    );
                } else {
                    $response["errorType"] = "error";
                    $response["error"] = "POST data incorrect: memberID, chapter";
                    echo json_encode($response);
                    exit;
                }
            } elseif ($level == "tnContinue") {
                if (isset($post["memberID"]) && isset($post["chapter"])) {
                    $eventData = $this->eventModel->getMemberEventsForNotes(
                        Session::get("memberID"),
                        $eventID,
                        $post["memberID"],
                        $post["chapter"]
                    );
                } else {
                    $response["errorType"] = "error";
                    $response["error"] = "POST data incorrect: memberID, chapter";
                    echo json_encode($response);
                    exit;
                }
            } elseif (in_array($level, ["tqContinue", "twContinue", "obsContinue", "bcContinue", "bcaContinue"])) {
                if (isset($post["memberID"]) && isset($post["chapter"])) {
                    $eventData = $this->eventModel->getMemberEventsForOther(
                        Session::get("memberID"),
                        $eventID,
                        $post["memberID"],
                        $post["chapter"]
                    );
                } else {
                    $response["errorType"] = "error";
                    $response["error"] = "POST data incorrect: memberID, chapter";
                    echo json_encode($response);
                    exit;
                }
            } elseif ($level == "radContinue") {
                if (isset($post["memberID"]) && isset($post["chapter"])) {
                    $eventData = $this->eventModel->getMemberEventsForRadio(
                        Session::get("memberID"),
                        $eventID,
                        $post["memberID"],
                        $post["chapter"]
                    );
                } else {
                    $response["errorType"] = "error";
                    $response["error"] = "POST data incorrect: memberID, chapter";
                    echo json_encode($response);
                    exit;
                }
            }

            if (!empty($eventData)) {
                $event = $eventData[0];
                $mode = $event->bookProject;

                switch ($event->step) {
                    case EventSteps::BLIND_DRAFT:
                    case EventSteps::REARRANGE:
                    case EventSteps::SYMBOL_DRAFT:
                        if ($event->step == EventSteps::SYMBOL_DRAFT)
                            $post["draft"] = $post["symbols"];

                        if ($mode == "tn"
                            && isset($event->isCheckerPage)
                            && $event->isCheckerPage) {
                            $memberType = EventMembers::CHECKER;
                        }

                        $response = $this->saveChunk($post, $event, $memberType);
                        break;

                    case EventSteps::MULTI_DRAFT:
                    case EventSteps::SELF_CHECK:
                    case EventSteps::PEER_REVIEW:
                    case EventSteps::KEYWORD_CHECK:
                    case EventSteps::CONTENT_REVIEW:
                    case EventSteps::THEO_CHECK:
                        if (in_array($mode, ["tn","tq","tw","rad","obs","bc","bca"])
                            && isset($event->isCheckerPage)
                            && $event->isCheckerPage) {
                            $memberType = EventMembers::CHECKER;
                        }

                        $response = $memberType == EventMembers::L2_CHECKER
                            ? $this->saveVerses($post, $event, $memberType)
                            : $this->saveChunks($post, $event, $memberType);
                        break;

                    case EventCheckSteps::PEER_EDIT_L3:
                        $response = $this->saveVerses($post, $event, $memberType);
                        break;
                }
            }
        }

        echo json_encode($response);
    }

    public function autoSaveVerseInputMode()
    {
        $response = array("success" => false);
        $post = Gump::xss_clean($_REQUEST);
        $eventID = isset($post["eventID"]) && is_numeric($post["eventID"]) ? $post["eventID"] : null;
        $isCheckingStep = $post["isChecking"] ?? false;
        $checkingChapter = $post["checkingChapter"] ?? null;

        if ($eventID !== null) {
            $event = $this->eventModel->getMemberEvents(
                Session::get("memberID"),
                $eventID,
                $checkingChapter,
                $isCheckingStep,
                false,
                false
            );

            if (!empty($event)) {
                switch ($event[0]->step) {
                    case EventSteps::MULTI_DRAFT:
                    case EventSteps::SELF_CHECK:
                    case EventSteps::PEER_REVIEW:
                        if ($event[0]->step == EventSteps::PEER_REVIEW && $event[0]->checkDone) {
                            $response["errorType"] = "checkDone";
                            $response["error"] = __("not_possible_to_save_error");
                            echo json_encode($response);
                            exit;
                        }

                        $response = $this->saveVersesWithMarkers($post, $event[0]);
                        break;
                }
            }
        }

        echo json_encode($response);
    }

    public function saveComment()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : null;
        $chunk = isset($_POST["chunk"]) && $_POST["chunk"] != "" ? (integer)$_POST["chunk"] : null;
        $comment = $_POST["comment"] ?? "";
        $level = isset($_POST["level"]) ? (integer)$_POST["level"] : 1;
        $autoSaver = filter_var($_POST["autoSaver"], FILTER_VALIDATE_BOOLEAN);
        $memberID = Session::get("memberID");
        $canSave = false;

        if ($eventID !== null && $chapter !== null && $chunk !== null) {
            $memberInfo = $this->eventModel->getEventMemberInfo($eventID, $memberID);
            foreach ($memberInfo as $info) {
                if ($info->translator == $memberID
                    || $info->l2checker == $memberID
                    || $info->l3checker == $memberID) {
                    $canSave = true;
                    break;
                }
            }

            if ($canSave) {
                $postdata = array(
                    "text" => $comment,
                );

                if ($comment != "") {
                    $postdata += array(
                        "eventID" => $eventID,
                        "chapter" => $chapter,
                        "chunk" => $chunk,
                        "memberID" => $memberID,
                        "level" => $level,
                        "saved" => !$autoSaver
                    );

                    $commentData = $this->translationModel->getComment($eventID, $chapter, $chunk, $memberID, $level, false);
                    if (empty($commentData)) {
                        $result = $this->translationModel->createComment($postdata);
                    } else {
                        $postdata = [
                            "text" => $comment,
                            "saved" => !$autoSaver
                        ];
                        $this->translationModel->updateComment($postdata, ["cID" => $commentData[0]->cID]);
                        $result = $commentData[0]->cID;
                    }

                    if ($result) {
                        $response["success"] = true;
                        $response["user"] = $this->member->firstName . " " . mb_substr($this->member->lastName, 0, 1);
                        $response["text"] = $comment;
                        $response["cID"] = $result;
                    }
                }
            }
        }

        echo json_encode($response);
    }

    public function deleteComment()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $cID = isset($_POST["cID"]) && $_POST["cID"] != "" ? (integer)$_POST["cID"] : null;
        $memberID = Session::get("memberID");

        if ($cID !== null) {
            $postdata = array(
                "cID" => $cID,
                "memberID" => $memberID
            );

            $result = $this->translationModel->deleteComment($postdata);

            if ($result) {
                $response["success"] = true;
            }
        }

        echo json_encode($response);
    }

    public function getKeywords()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : null;

        if ($eventID !== null && $chapter !== null) {
            $keywords = $this->translationModel->getKeywords([
                "eventID" => $eventID,
                "chapter" => $chapter
            ]);

            if (!empty($keywords)) {
                $response["success"] = true;
                $response["text"] = $keywords;
            }
        }

        echo json_encode($response);
    }

    public function saveKeyword()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : null;
        $chunk = isset($_POST["chunk"]) && $_POST["chunk"] != "" ? (integer)$_POST["chunk"] : null;
        $index = isset($_POST["index"]) && $_POST["index"] != "" ? (integer)$_POST["index"] : null;
        $verse = isset($_POST["verse"]) ? $_POST["verse"] : "";
        $text = isset($_POST["text"]) ? $_POST["text"] : "";
        $remove = isset($_POST["remove"]) && $_POST["remove"] == "true";
        $memberID = Session::get("memberID");

        if ($eventID !== null && $chapter !== null && $chunk !== null && $index > -1 && $verse != null) {
            $memberInfo = $this->eventModel->getEventMemberInfo($eventID, $memberID);

            $canKeyword = false;
            $canCreate = true;

            if (!empty($memberInfo) || Session::get("isSuperAdmin")) {
                foreach ($memberInfo as $info) {
                    if (!in_array($info->bookProject, ["tn"])) {
                        if ($info->bookProject == "sun" && $info->translator == $memberID) {
                            $events = $this->eventModel->getMemberEventsForSun(
                                $memberID,
                                $eventID,
                                null,
                                $chapter
                            );

                            foreach ($events as $event) {
                                if ($event->step == EventSteps::THEO_CHECK) {
                                    if ($chapter == $event->currentChapter) {
                                        $canKeyword = true;
                                        break;
                                    }
                                }
                            }
                        } elseif ($info->translator == $memberID) {
                            $events = $this->eventModel->getMemberEventsForChecker($memberID, $eventID, null, $chapter);
                            foreach ($events as $event) {
                                if ($event->step == EventSteps::KEYWORD_CHECK) {
                                    $canKeyword = true;
                                    break;
                                }
                            }
                        } elseif ($info->l2checker == $memberID) {
                            $canCreate = false;
                            $events = $this->eventModel->getMemberEventsForRevisionChecker(
                                $memberID,
                                $eventID,
                                null,
                                $chapter
                            );
                            foreach ($events as $event) {
                                if ($event->step == EventCheckSteps::KEYWORD_CHECK
                                    || $event->step == EventCheckSteps::CONTENT_REVIEW) {
                                    if ($chapter == $event->currentChapter) {
                                        $canKeyword = true;
                                        break;
                                    }
                                }
                            }
                        } elseif (Session::get("isSuperAdmin")) {
                            $canKeyword = true;
                        }
                    } else {
                        $events = $this->eventModel->getMemberEventsForNotes(
                            $memberID,
                            $eventID,
                            null,
                            $chapter
                        );

                        foreach ($events as $event) {
                            if ($event->step == EventSteps::HIGHLIGHT) {
                                if ($chapter == $event->currentChapter) {
                                    $canKeyword = true;
                                    break;
                                }
                            }
                        }
                    }
                }

                if ($canKeyword) {
                    $result = null;

                    $keyword = $this->translationModel->getKeywords([
                        "eventID" => $eventID,
                        "chapter" => $chapter,
                        "chunk" => $chunk,
                        "verse" => $verse,
                        "indexOrder" => $index,
                        "text" => $text
                    ]);

                    if (!empty($keyword)) {
                        if ($remove) {
                            $response["type"] = "remove";
                            $result = $this->translationModel->deleteKeyword($keyword[0]->kID);
                        } else {
                            $response["error"] = __("keyword_exists_error");
                            echo json_encode($response);
                            return;
                        }
                    } else {
                        if ($canCreate) {
                            $postdata = [
                                "eventID" => $eventID,
                                "chapter" => $chapter,
                                "chunk" => $chunk,
                                "verse" => $verse,
                                "indexOrder" => $index,
                                "text" => $text,
                                "memberID" => Session::get("memberID")
                            ];

                            $response["type"] = "add";
                            $result = $this->translationModel->createKeyword($postdata);
                        }
                    }

                    if ($result) {
                        $response["success"] = true;
                        $response["text"] = $text;
                    }
                }
            }
        }

        echo json_encode($response);
    }

    /**
     * Make member a level 1 checker, who picks from notification area
     * @param $eventID
     * @param $memberID
     * @param $step
     * @return mixed
     */
    public function applyChecker($eventID, $memberID, $chapter, $step)
    {
        $canApply = false;
        $notif = null;

        foreach ($this->notifications as $notification) {
            if ($eventID == $notification->eventID
                && $memberID == $notification->memberID
                && $chapter == $notification->currentChapter
                && $step == $notification->step) {
                if ($notification->checkerID == 0) {
                    $canApply = true;
                    $notif = $notification;
                    break;
                }
            }
        }

        if ($canApply && $notif) {
            $postData = [];
            switch ($step) {
                case EventSteps::PEER_REVIEW:
                    $peerCheck = (array)json_decode($notif->peerCheck, true);
                    if (isset($peerCheck[$chapter])) {
                        $peerCheck[$chapter] = ["memberID" => Session::get("memberID"), "done" => 0];
                    }
                    $postData["peerCheck"] = json_encode($peerCheck);
                    break;
                case EventSteps::KEYWORD_CHECK:
                    $kwCheck = (array)json_decode($notif->kwCheck, true);
                    if (isset($kwCheck[$chapter])) {
                        $kwCheck[$chapter] = ["memberID" => Session::get("memberID"), "done" => 0];
                    }
                    $postData["kwCheck"] = json_encode($kwCheck);
                    break;
                case EventSteps::CONTENT_REVIEW:
                    $crCheck = (array)json_decode($notif->crCheck, true);
                    if (isset($crCheck[$chapter])) {
                        $crCheck[$chapter] = ["memberID" => Session::get("memberID"), "done" => 0];
                    }
                    $postData["crCheck"] = json_encode($crCheck);
            }
            $this->eventModel->updateTranslator($postData, array("eventID" => $eventID, "memberID" => $memberID));
            Url::redirect("events/checker/$eventID/$memberID/$chapter");
        } else {
            $error[] = __("cannot_apply_checker");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;

        return View::make("Events/CheckerApply")
            ->shares("title", __("apply_checker_l1"))
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * Make member a checker for TN, TQ, TW, OBS, BC, BCA, who picks from notification area
     * @param $eventID
     * @param $memberID
     * @param $chapter
     * @return mixed
     */
    public function applyCheckerOther($bookProject, $eventID, $memberID, $chapter)
    {
        $canApply = false;
        $notif = null;

        foreach ($this->notifications as $notification) {
            if ($bookProject == $notification->bookProject
                && $eventID == $notification->eventID
                && $memberID == $notification->memberID
                && $chapter == $notification->currentChapter) {
                $postdata = [];

                if ($notification->peer == 1) {
                    $otherCheck = (array)json_decode($notification->otherCheck, true);
                    if (isset($otherCheck[$chapter]) && $otherCheck[$chapter]["memberID"] == 0) {
                        $otherCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->otherCheck = json_encode($otherCheck);
                        $notif = $notification;

                        $postdata = ["otherCheck" => $notif->otherCheck];
                        $canApply = true;
                    }
                } else {
                    $peerCheck = (array)json_decode($notification->peerCheck, true);
                    if (isset($peerCheck[$chapter]) && $peerCheck[$chapter]["memberID"] == 0) {
                        $peerCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->peerCheck = json_encode($peerCheck);
                        $notif = $notification;

                        $postdata = ["peerCheck" => $notif->peerCheck];
                        $canApply = true;
                    }
                }
            }
        }

        if ($canApply && $notif !== null) {
            $this->eventModel->updateTranslator(
                $postdata,
                array(
                    "eventID" => $eventID,
                    "memberID" => $memberID));

            Url::redirect("events/checker-$bookProject/$eventID/$memberID/$chapter");
            exit;
        } else {
            $error[] = __("cannot_apply_checker");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;

        return View::make("Events/CheckerApply")
            ->shares("title", __("apply_checker_l1"))
            ->shares("data", $data)
            ->shares("error", @$error);
        exit;
    }

    /**
     * Make member a revision checker, who picks from notification area
     * @param $eventID
     * @param $memberID
     * @param $step
     * @param $chapter
     * @return mixed
     */
    public function applyCheckerL2L3($eventID, $memberID, $step, $chapter)
    {
        $canApply = false;
        $notif = null;

        foreach ($this->notifications as $notification) {
            if ($eventID == $notification->eventID
                && $memberID == $notification->memberID
                && $step == $notification->step
                && $chapter == $notification->currentChapter) {
                if ($step == EventCheckSteps::PEER_REVIEW) {
                    $peerCheck = (array)json_decode($notification->peerCheck, true);
                    if (isset($peerCheck[$chapter]) && $peerCheck[$chapter]["memberID"] == 0) {
                        $peerCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->peerCheck = json_encode($peerCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                } elseif ($step == EventCheckSteps::KEYWORD_CHECK) {
                    $kwCheck = (array)json_decode($notification->kwCheck, true);
                    if (isset($kwCheck[$chapter]) && $kwCheck[$chapter]["memberID"] == 0) {
                        $kwCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->kwCheck = json_encode($kwCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                } elseif ($step == EventCheckSteps::CONTENT_REVIEW) {
                    $crCheck = (array)json_decode($notification->crCheck, true);
                    if (isset($crCheck[$chapter]) && $crCheck[$chapter]["memberID"] == 0) {
                        $crCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->crCheck = json_encode($crCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                } elseif ($step == EventCheckSteps::PEER_REVIEW_L3) {
                    $peerCheck = (array)json_decode($notification->peerCheck, true);
                    if (isset($peerCheck[$chapter]) && $peerCheck[$chapter]["memberID"] == 0) {
                        $peerCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->peerCheck = json_encode($peerCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                }
            }
        }

        if ($canApply && $notif) {
            if ($notif->manageMode == "l2") {
                $postdata = [
                    "peerCheck" => $notif->peerCheck,
                    "kwCheck" => $notif->kwCheck,
                    "crCheck" => $notif->crCheck,
                ];
                $this->eventModel->updateL2Checker($postdata, [
                    "eventID" => $eventID,
                    "memberID" => $memberID
                ]);

                $sun = ($notif->bookProject == "sun" ? "-sun" : "");
                Url::redirect("events/checker$sun-revision/$eventID/$memberID/$chapter");
            } elseif ($notif->manageMode == "l3") {
                $postdata = [
                    "peerCheck" => $notif->peerCheck,
                ];
                $this->eventModel->updateL3Checker($postdata, [
                    "eventID" => $eventID,
                    "memberID" => $memberID
                ]);

                $project = (!in_array($notif->bookProject, ["ulb", "udb"]) ? "-" . $notif->bookProject : "");
                Url::redirect("events/checker$project-review/$eventID/$memberID/$chapter");
            }
        } else {
            $error[] = __("cannot_apply_checker");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;

        return View::make("Events/CheckerApply")
            ->shares("title", __("apply_checker_l1"))
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * Make member a SUN checker, who picks from notification area
     * @param $eventID
     * @param $memberID
     * @param $step
     * @param $chapter
     * @return mixed
     */
    public function applyCheckerSun($eventID, $memberID, $step, $chapter)
    {
        $canApply = false;
        $notif = null;

        foreach ($this->notifications as $notification) {
            if ($eventID == $notification->eventID
                && $memberID == $notification->memberID
                && $step == $notification->step
                && $chapter == $notification->currentChapter) {
                if ($step == EventSteps::THEO_CHECK) {
                    $kwCheck = (array)json_decode($notification->kwCheck, true);
                    if (isset($kwCheck[$chapter]) && $kwCheck[$chapter]["memberID"] == 0) {
                        $kwCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->kwCheck = json_encode($kwCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                } elseif ($step == EventSteps::CONTENT_REVIEW) {
                    $crCheck = (array)json_decode($notification->crCheck, true);
                    if (isset($crCheck[$chapter]) && $crCheck[$chapter]["memberID"] == 0) {
                        $crCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->crCheck = json_encode($crCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                }
            }
        }

        if ($canApply && $notif) {
            $postdata = [
                "kwCheck" => $notif->kwCheck,
                "crCheck" => $notif->crCheck,
            ];
            $this->eventModel->updateTranslator($postdata, [
                "eventID" => $eventID,
                "memberID" => $memberID
            ]);

            $odb = ($notif->sourceBible == "odb" ? "-odb" : "");
            Url::redirect("events/checker$odb-sun/$eventID/$memberID/$chapter");
        } else {
            $error[] = __("cannot_apply_checker");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;

        return View::make("Events/CheckerApply")
            ->shares("title", __("apply_checker_sun"))
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * Make member a RADIO checker, who picks from notification area
     * @param $eventID
     * @param $memberID
     * @param $step
     * @param $chapter
     * @return mixed
     */
    public function applyCheckerRadio($eventID, $memberID, $step, $chapter)
    {
        $canApply = false;
        $notif = null;

        foreach ($this->notifications as $notification) {
            if ($eventID == $notification->eventID
                && $memberID == $notification->memberID
                && $step == $notification->step
                && $chapter == $notification->currentChapter) {
                if ($step == EventSteps::PEER_REVIEW) {
                    $peerCheck = (array)json_decode($notification->peerCheck, true);
                    if (isset($peerCheck[$chapter]) && $peerCheck[$chapter]["memberID"] == 0) {
                        $peerCheck[$chapter]["memberID"] = Session::get("memberID");
                        $notification->peerCheck = json_encode($peerCheck);
                        $notif = $notification;
                        $canApply = true;
                    }
                }
            }
        }

        if ($canApply && $notif) {
            $postdata = [
                "peerCheck" => $notif->peerCheck
            ];
            $this->eventModel->updateTranslator($postdata, [
                "eventID" => $eventID,
                "memberID" => $memberID
            ]);

            Url::redirect("events/checker-rad/$eventID/$memberID/$chapter");
        } else {
            $error[] = __("cannot_apply_checker");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;

        return View::make("Events/CheckerApply")
            ->shares("title", __("apply_checker_sun"))
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    /**
     * Make member a verbalize checker
     */
    public function applyVerbChecker()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $chkID = isset($_POST["chkID"]) && $_POST["chkID"] != "" ? (integer)$_POST["chkID"] : null;
        $chkName = isset($_POST["chkName"]) && preg_match("/^[^\d!@#\$%\^&\*\(\)_\-\+=\.,\?\/\\\[\]\{\}\|\"]+$/", $_POST["chkName"]) ? trim($_POST["chkName"]) : null;
        $memberID = Session::get("memberID");

        if ($eventID !== null && ($chkID != null || $chkName != null)) {
            $event = $this->eventModel->getMemberEvents($memberID, $eventID);
            if ($chkID != null) {
                $chkMember = $this->membersModel->getMembers([$chkID]);
                if (!empty($chkMember))
                    $chkName = $chkMember[0]->firstName . " " . mb_substr($chkMember[0]->lastName, 0, 1) . ".";
                else {
                    $chkID = null;
                    $chkName = null;
                }
            }

            if (!empty($event) && $chkName != null) {
                $verbCheck = (array)json_decode($event[0]->verbCheck, true);
                $checker = $chkID != null ? $chkID : $chkName;

                if ($event[0]->step == EventSteps::VERBALIZE && !array_key_exists($event[0]->currentChapter, $verbCheck)) {
                    $verbCheck[$event[0]->currentChapter] = ["memberID" => $checker, "done" => 1];
                    $postdata["verbCheck"] = json_encode($verbCheck);

                    $upd = $this->eventModel->updateTranslator($postdata, array("eventID" => $eventID, "memberID" => $memberID));
                    if ($upd) {
                        $response["success"] = true;
                        $response["chkName"] = $chkName;
                    } else {
                        $response["error"] = "not_saved";
                    }
                } else {
                    $response["error"] = "wrong_step";
                }
            } else {
                $response["error"] = "wrong_event_or_member";
            }
        } else {
            $response["error"] = "forbidden_name_format";
        }

        echo json_encode($response);
    }

    public function applyNotification($noteID) {
        $noteModel = $this->eventRepo->getToNotifications(Session::get("memberID"))
            ->filter(function($item) use ($noteID) {
                return $item->noteID == $noteID;
            })->first();
        if ($noteModel) {
            switch ($noteModel->type) {
                case NotificationType::READY:
                case NotificationType::DONE:
                    $notification = NotificationMapper::toData($noteModel);
                    $note = new Notification($notification);
                    $url = $note->getDrafterUrl();
                    $noteModel->delete();

                    Url::redirect($url);
                default:
                    $error[] ="Not implemented";
            }
        } else {
            $error[] = __("notification_error");
        }

        $data["menu"] = 1;
        $data["notifications"] = $this->renderedNotifications;

        return View::make("Events/NotificationApply")
            ->shares("title", __("apply_notification"))
            ->shares("data", $data)
            ->shares("error", @$error);
    }

    public function checkEvent()
    {
        $response = array("success" => false);

        $_POST = Gump::xss_clean($_POST);

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;

        $event = $this->eventRepo->get($eventID);

        if ($event && $event->translators->contains(Session::get("memberID")) && $event->state != 'started') {
            $response["success"] = true;
        }

        echo json_encode($response);
    }

    /**
     * Get notifications for user
     */
    public function getNotifications()
    {
        $data["notifications"] = [];

        if (!empty($this->renderedNotifications)) {
            $data["notifications"] = $this->renderedNotifications;
        } else {
            $data["no_notifications"] = __("no_notifs_msg");
        }

        $data["success"] = true;
        echo json_encode($data);
    }


    //-------------------- Functions --------------------------//

    /**
     * Get Scripture source text for chapter or chunk
     * @param $data
     * @param bool $getChunk
     * @return array
     */
    private function getScriptureSourceText($data, $getChunk = false)
    {
        $currentChapter = $data["event"][0]->currentChapter;
        $currentChunk = $data["event"][0]->state == EventStates::TRANSLATING
            ? $data["event"][0]->currentChunk : 0;

        $initChapter = $data["event"][0]->bookProject != "tn" ? 0 : -1;
        $currentChunkText = [];
        $chunks = json_decode($data["event"][0]->chunks, true);
        $data["chunks"] = $chunks;

        if ($currentChapter == $initChapter) {
            $level = "l1";
            if ($data["event"][0]->state == EventStates::L2_CHECK) {
                $level = "l2";
                $memberID = $data["event"][0]->memberID;
            } elseif ($data["event"][0]->state == EventStates::L3_CHECK) {
                $level = "l3";
                $memberID = $data["event"][0]->memberID;
            } else {
                $memberID = $data["event"][0]->myMemberID;
            }

            $nextChapter = $this->eventModel->getNextChapter(
                $data["event"][0]->eventID,
                $memberID,
                $level);
            if (!empty($nextChapter))
                $currentChapter = $nextChapter[0]->chapter;
        }

        if ($currentChapter <= $initChapter) [];

        $usfm = $this->resourcesRepo->getScripture(
            $data["event"][0]->sourceLangID,
            $data["event"][0]->sourceBible,
            $data["event"][0]->bookCode,
            $data["event"][0]->sort,
            $currentChapter
        );

        if (!empty($usfm)) {
            $data["text"] = $usfm["text"];
            $data["totalVerses"] = $usfm["totalVerses"];
            $data["currentChapter"] = $currentChapter;
            $data["currentChunk"] = $currentChunk;

            $data["media"] = $this->resourcesRepo->getMedia(
                $data["event"][0]->targetLang,
                $data["event"][0]->sourceBible,
                $data["event"][0]->bookCode,
                $currentChapter
            );

            if ($getChunk) {
                $chapData = $chunks;
                $chunk = $chapData[$currentChunk];
                $fv = $chunk[0];
                $lv = $chunk[sizeof($chunk) - 1];

                $data["no_chunk_source"] = true;

                foreach ($data["text"] as $verse => $text) {
                    $v = explode("-", $verse);
                    $map = array_map(function ($value) use ($fv, $lv) {
                        return $value >= $fv && $value <= $lv;
                    }, $v);
                    $map = array_unique($map);

                    if ($map[0]) {
                        $currentChunkText[$verse] = $text;
                        $data["no_chunk_source"] = false;
                    }
                }

                $data["chunks"] = $chapData;
                $data["chunk"] = $chunk;
                $data["totalVerses"] = sizeof($chunk);

                $data["text"] = $currentChunkText;
            }

            return $data;
        } else {
            return array("error" => __("no_source_error"));
        }
    }

    public function getNotesSourceText($data, $getChunk = false)
    {
        $currentChapter = $data["event"][0]->currentChapter;
        $currentChunk = $data["event"][0]->currentChunk;

        if ($currentChapter == -1) {
            $nextChapter = $this->eventModel->getNextChapter($data["event"][0]->eventID, $data["event"][0]->myMemberID);
            if (!empty($nextChapter))
                $currentChapter = $nextChapter[0]->chapter;
        }

        if ($currentChapter <= -1) return false;

        $notes = $this->resourcesRepo->getMdResource(
            $data["event"][0]->resLangID,
            $data["event"][0]->bookProject,
            $data["event"][0]->bookCode,
            $currentChapter,
            true
        );

        if (!empty($notes)) {
            $data["notes"] = $notes;
            $data["currentChapter"] = $currentChapter;
            $data["currentChunk"] = $currentChunk;

            $chunks = json_decode($data["event"][0]->chunks, true);
            $data["chunks"] = $chunks;

            if ($currentChapter > 0) {
                if (isset($data["text"]) && $data["text"] != "") {
                    $data["nosource"] = false;
                } else {
                    $data["no_chunk_source"] = true;
                    $data["nosource"] = true;
                }
            } else {
                $data["nosource"] = true;
            }

            if ($getChunk) {
                $data["notes"] = [];

                if (isset($data["chunk"])) {
                    foreach ($data["chunk"] as $verse) {
                        foreach ($notes[$verse] as $note) {
                            $data["notes"][] = $note;
                        }
                        break;
                    }
                } else {
                    $data["notes"] = $notes[$currentChunk];
                    $data["chunk"][0] = $currentChunk;
                }
            }

            return $data;
        } else {
            return array("error" => __("no_source_error"));
        }
    }

    private function getQuestionsSourceText($data)
    {
        $currentChapter = $data["event"][0]->currentChapter;
        $currentChunk = $data["event"][0]->currentChunk;

        if ($currentChapter == 0) {
            $nextChapter = $this->eventModel->getNextChapter($data["event"][0]->eventID, $data["event"][0]->myMemberID);
            if (!empty($nextChapter))
                $currentChapter = $nextChapter[0]->chapter;
        }

        if ($currentChapter <= 0) return false;

        $questions = $this->resourcesRepo->getMdResource(
            $data["event"][0]->resLangID,
            $data["event"][0]->bookProject,
            $data["event"][0]->bookCode,
            $currentChapter,
            true
        );

        if (!empty($questions)) {
            $data["questions"] = $questions;
            $data["currentChapter"] = $currentChapter;
            $data["currentChunk"] = $currentChunk;

            end($data["questions"]);
            $data["totalVerses"] = key($data["questions"]);

            $chunks = json_decode($data["event"][0]->chunks, true);
            $data["chunks"] = $chunks;

            $data["nosource"] = false;

            return $data;
        } else {
            return array("error" => __("no_source_error"));
        }
    }

    public function getOtherSourceText($data, $getChunk = false)
    {
        $currentChapter = $data["event"][0]->currentChapter;
        $currentChunk = $data["event"][0]->state == EventStates::TRANSLATING
            ? $data["event"][0]->currentChunk : 0;

        $source = $this->resourcesRepo->getOtherResource(
            $data["event"][0]->sourceLangID,
            $data["event"][0]->sourceBible,
            $data["event"][0]->bookCode
        );

        if (!empty($source)) {
            $initChapter = 0;
            $currentChunkText = [];
            $chunks = json_decode($data["event"][0]->chunks, true);
            $data["chunks"] = $chunks;

            if ($currentChapter == $initChapter) {
                $memberID = $data["event"][0]->myMemberID;

                $nextChapter = $this->eventModel->getNextChapter(
                    $data["event"][0]->eventID,
                    $memberID);
                if (!empty($nextChapter))
                    $currentChapter = $nextChapter[0]->chapter;
            }

            if ($currentChapter <= $initChapter) return false;

            if (!isset($source["chapters"][$currentChapter])) {
                return array("error" => __("no_source_error"));
            }

            $data["text"] = $source["chapters"][$currentChapter];

            $lastVerse = sizeof($data["text"]);
            $data["totalVerses"] = $lastVerse;
            $data["currentChapter"] = $currentChapter;
            $data["currentChunk"] = $currentChunk;

            $data["chapters"] = [];
            for ($i = 1; $i <= sizeof($source["chapters"]); $i++) {
                $data["chapters"][$i] = [];
            }

            $chapters = $this->eventModel->getChapters($data["event"][0]->eventID);

            foreach ($chapters as $chapter) {
                $tmp["trID"] = $chapter["trID"];
                $tmp["memberID"] = $chapter["memberID"];
                $tmp["chunks"] = json_decode($chapter["chunks"], true);
                $tmp["done"] = $chapter["done"];

                $data["chapters"][$chapter["chapter"]] = $tmp;
            }

            if ($getChunk) {
                $chapData = $chunks;
                $chunk = $chapData[$currentChunk];
                $fv = $chunk[0];
                $lv = $chunk[sizeof($chunk) - 1];

                $data["no_chunk_source"] = true;

                if (isset($data["text"][$fv])) {
                    $currentChunkText[$fv] = $data["text"][$fv];
                    $data["no_chunk_source"] = false;
                }

                $data["chunks"] = $chapData;
                $data["chunk"] = $chunk;
                $data["totalVerses"] = sizeof($chunk);

                $data["text"] = $currentChunkText;
            }

            return $data;
        } else {
            return array("error" => __("no_source_error"));
        }
    }

    private function getWordsSourceText($data)
    {
        $currentChapter = $data["event"][0]->currentChapter;
        $currentChunk = $data["event"][0]->currentChunk;

        if ($currentChapter == 0) {
            $nextChapter = $this->eventModel->getNextChapter($data["event"][0]->eventID, $data["event"][0]->myMemberID);
            if (!empty($nextChapter))
                $currentChapter = $nextChapter[0]->chapter;
        }

        if ($currentChapter <= 0) return false;

        $words = $this->resourcesRepo->getTw(
            $data["event"][0]->resLangID,
            $data["event"][0]->name,
            $data["event"][0]->eventID,
            $currentChapter,
            true
        );

        if (!empty($words)) {
            $data["words"] = $words["words"];
            $data["group"] = $words["group"];
            $data["currentChapter"] = $currentChapter;
            $data["currentChunk"] = $currentChunk;

            $chunks = json_decode($data["event"][0]->chunks, true);
            $data["chunks"] = $chunks;

            $data["nosource"] = false;

            return $data;
        } else {
            return array("error" => __("no_source_error"));
        }
    }

    public function checkBookFinished($chapters, $chaptersNum, $other = false, $level = 1)
    {
        if (isset($chapters) && is_array($chapters) && !empty($chapters)) {
            $chaptersDone = 0;
            foreach ($chapters as $chapter) {
                $chk = $level == 3 ? "l3checked" : ($level == 2 ? "l2checked" : ($other ? "checked" : "done"));
                if (!empty($chapter) && $chapter[$chk])
                    $chaptersDone++;
            }

            if ($chaptersNum == $chaptersDone)
                return true;
        }

        return false;
    }

    public function getTq($bookCode, $chapter, $lang)
    {
        $data = [];
        $data["questions"] = $this->resourcesRepo->getMdResource(
            $lang,
            "tq",
            $bookCode,
            $chapter,
            true
        );

        $this->layout = "dummy";
        echo View::make("Events/Tools/Tq")
            ->shares("data", $data)
            ->renderContents();
    }

    public function getTn($bookCode, $chapter, $lang, $totalVerses)
    {
        $data = [];

        $data["notes"] = $this->resourcesRepo->getMdResource(
            $lang,
            "tn",
            $bookCode,
            $chapter,
            true
        );

        $data["totalVerses"] = $totalVerses;
        $data["notesVerses"] = $this->apiModel->getNotesVerses($data);

        $this->layout = "dummy";
        echo View::make("Events/Tools/Tn")
            ->shares("data", $data)
            ->renderContents();
    }

    public function getTw($bookCode, $chapter, $lang)
    {
        $data = [];

        $data["keywords"] = $this->resourcesRepo->parseTwByBook(
            $lang,
            $bookCode,
            $chapter,
            true
        );

        $this->layout = "dummy";
        echo View::make("Events/Tools/Tw")
            ->shares("data", $data)
            ->renderContents();
    }

    public function getBc($bookCode, $chapter, $lang)
    {
        $data = [];

        $data["commentaries"] = $this->resourcesRepo->getBc(
            $lang,
            $bookCode,
            $chapter,
            true
        );

        $this->layout = "dummy";
        echo View::make("Events/Tools/Bc")
            ->shares("data", $data)
            ->renderContents();
    }

    public function getBcArticle($lang, $article) 
    {
        $data = [];

        $data["article"] = $this->resourcesRepo->getBcArticle(
            $lang,
            $article,
            true
        );

        $this->layout = "dummy";
        echo View::make("Events/Tools/BcArticle")
            ->shares("data", $data)
            ->renderContents();
    }

    public function getRubric($lang)
    {
        $data = [];
        $data["rubric"] = $this->resourcesRepo->getQaGuide($lang);

        $this->layout = "dummy";
        echo View::make("Events/Tools/Rubric")
            ->shares("data", $data)
            ->renderContents();
    }

    public function getSailDict()
    {
        $data = [];
        $data["saildict"] = $this->sailDictModel->getSunDictionary();

        $this->layout = "dummy";
        echo View::make("Events/Tools/SailDict")
            ->shares("data", $data)
            ->renderContents();
    }

    public function createNotification() {
        $_POST = Gump::xss_clean($_POST);
        $response = ["success" => false];

        $eventID = isset($_POST["eventID"]) && $_POST["eventID"] != "" ? (integer)$_POST["eventID"] : null;
        $toMemberID = isset($_POST["to"]) && $_POST["to"] != "" ? (integer)$_POST["to"] : null;
        $fromMemberID = Session::get("memberID");
        $step = isset($_POST["step"]) && $_POST["step"] != "" ? $_POST["step"] : null;
        $chapter = isset($_POST["chapter"]) && $_POST["chapter"] != "" ? (integer)$_POST["chapter"] : null;
        $manageMode = isset($_POST["manageMode"]) && $_POST["manageMode"] != "" ? $_POST["manageMode"] : null;
        $type = isset($_POST["type"]) && $_POST["type"] != "" ? $_POST["type"] : NotificationType::READY;

        if ($eventID && $step && $chapter !== null) {
            if ($type != NotificationType::STARTED && !$toMemberID) {
                $response["error"] = __("error_occurred", ["wrong parameters"]);
                echo json_encode($response);
                exit;
            }

            $exists = $this->eventRepo->notificationExists(
                $eventID,
                $fromMemberID,
                $toMemberID,
                $manageMode,
                $step,
                $chapter,
                $type
            );

            if ($exists) {
                $response["success"] = true;
            } else {
                $event = $this->eventRepo->get($eventID);
                $fromMember = $this->memberRepo->get($fromMemberID);
                $toMember = $this->memberRepo->get($toMemberID);

                $fromMemberInEvent = EventUtil::isMemberInEvent($fromMember, $event);
                $toMemberInEvent = !$toMember || EventUtil::isMemberInEvent($toMember, $event);

                if ($fromMemberInEvent && $toMemberInEvent) {
                    $data = [
                        "manageMode" => $manageMode,
                        "step" => $step,
                        "currentChapter" => $chapter,
                        "type" => $type
                    ];
                    $notification = $this->eventRepo->createNotification($data, $event, $fromMember, $toMember);
                    if ($notification) {
                        $response["success"] = true;
                    }
                } else {
                    $response["error"] = __("error_occurred", ["wrong parameters"]);
                }
            }
        } else {
            $response["error"] = __("error_occurred", ["wrong parameters"]);
        }
        
        echo json_encode($response);
    }

    public function checkInternet() {
        return time();
    }

    private function getChapters($eventID) {
        $event = $this->eventRepo->get($eventID);

        $chapters = [];
        for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        $chaptersDB = $this->eventModel->getChapters($eventID);

        foreach ($chaptersDB as $chapter) {
            $tmp["trID"] = $chapter["trID"];
            $tmp["memberID"] = $chapter["memberID"];
            $tmp["chunks"] = json_decode($chapter["chunks"], true);
            $tmp["done"] = $chapter["done"];
            $tmp["checked"] = $chapter["checked"];
            $tmp["l2checked"] = $chapter["l2checked"];
            $tmp["l3checked"] = $chapter["l3checked"];

            $chapters[$chapter["chapter"]] = $tmp;
        }
        return $chapters;
    }

    private function sendBookCompletedNotif($event, $level = 1) {
        if(Config::get("app.type") == "remote") {
            switch ($level) {
                case 3:
                    $stage = EventStates::L3_CHECK;
                    break;
                case 2:
                    $stage = EventStates::L2_CHECK;
                    break;
                default:
                    $stage = EventStates::TRANSLATED;
            }

            Language::instance('app')->load("messages", "En");

            $emails = $event->admins->map(function($item) {
                return $item->email;
            })->toArray();

            $data = [
                "book" => $event->bookInfo->name,
                "language" => $event->project->gatewayLanguage->language->langName,
                "project" => $event->project->bookProject,
                "target" => $event->project->targetLanguage->langName,
                "level" => $stage
            ];

            AMQPMailer::sendView(
                "Emails/Manage/BookCompletedNotification",
                $data,
                $emails,
                Language::instance('app')->get("book_completed_msg", "En")
            );
        }
    }
}
