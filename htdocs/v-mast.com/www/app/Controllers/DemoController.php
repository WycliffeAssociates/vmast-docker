<?php
/**
 * Created by mXaln
 */

namespace App\Controllers;

use App\Domain\RenderNotifications;
use App\Helpers\DemoData;
use App\Models\SailDictionaryModel;
use Helpers\Constants\InputMode;
use Support\Facades\View;
use Config\Config;
use Helpers\Url;
use App\Core\Controller;
use Helpers\Constants\EventSteps;
use Helpers\Constants\EventCheckSteps;
use stdClass;

class DemoController extends Controller {
    private $renderNotifications;

    public function __construct() {
        parent::__construct();

        if (Config::get("app.isMaintenance")
            && !in_array($_SERVER['REMOTE_ADDR'], Config::get("app.ips"))) {
            Url::redirect("maintenance");
        }

        $this->renderNotifications = new RenderNotifications();
    }

    public function demo($page = null) {
        if (!isset($page))
            Url::redirect("events/demo/pray");

        $data["bookCode"] = "2ti";
        $data["bookName"] = "2 Timothy";
        $data["targetLangName"] = "English";
        $data["currentChapter"] = 2;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "en-x-demo1";
        $data["inputMode"] = InputMode::NORMAL;

        $notifications = DemoData::getScriptureNotifications(
            "ulb",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"]
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["next_step"] = EventSteps::PRAY;
        $data["comments"] = DemoData::getComments();

        $view = View::make("Events/L1/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/L1/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME;
                break;

            case "consume":
                $view->nest("page", "Events/L1/Demo/Consume");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::VERBALIZE;
                break;

            case "verbalize":
                $view->nest("page", "Events/L1/Demo/Verbalize");
                $data["step"] = EventSteps::VERBALIZE;
                $data["next_step"] = EventSteps::CHUNKING;
                break;

            case "chunking":
                $view->nest("page", "Events/L1/Demo/Chunking");
                $data["step"] = EventSteps::CHUNKING;
                $data["next_step"] = EventSteps::BLIND_DRAFT;
                break;

            case "read_chunk":
                $view->nest("page", "Events/L1/Demo/ReadChunk");
                $data["step"] = EventSteps::READ_CHUNK;
                $data["next_step"] = "continue_alt";
                break;

            case "blind_draft":
                $view->nest("page", "Events/L1/Demo/BlindDraft");
                $data["step"] = EventSteps::BLIND_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/L1/Demo/SelfCheck");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW;
                break;

            case "peer_review":
                $view->nest("page", "Events/L1/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = EventSteps::KEYWORD_CHECK;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/L1/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "keyword_check":
                $view->nest("page", "Events/L1/Demo/KeywordCheck");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::CONTENT_REVIEW;
                break;

            case "keyword_check_checker":
                $view->nest("page", "Events/L1/Demo/KeywordCheckChecker");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "content_review":
                $view->nest("page", "Events/L1/Demo/ContentReview");
                $data["step"] = EventSteps::CONTENT_REVIEW;
                $data["next_step"] = EventSteps::FINAL_REVIEW;
                break;

            case "content_review_checker":
                $view->nest("page", "Events/L1/Demo/ContentReviewChecker");
                $data["step"] = EventSteps::CONTENT_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "final_review":
                $view->nest("page", "Events/L1/Demo/FinalReview");
                $data["step"] = EventSteps::FINAL_REVIEW;
                $data["next_step"] = "continue_alt";
                break;

            case "information":
                return View::make("Events/L1/Demo/Information")
                    ->shares("title", __("event_info"))
                    ->shares("data", $data);
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoScriptureInput($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-scripture-input/pray");

        $data["notifications"] = [];
        $data["comments"] = DemoData::getHelpComments();

        $data["isDemo"] = true;
        $data["inputMode"] = InputMode::SCRIPTURE_INPUT;
        $data["menu"] = 5;
        $data["next_step"] = EventSteps::PRAY;

        $data["bookCode"] = "2ti";
        $data["currentChapter"] = 2;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "id";

        $view = View::make("Events/L1/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/L1/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = "multi-draft_input_mode";
                break;

            case "input":
                $view->nest("page", "Events/L1/Demo/Input");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/L1/Demo/SelfCheckInputMode");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "information":
                return View::make("Events/L1/Demo/Information")
                    ->shares("title", __("event_info"))
                    ->shares("data", $data);
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoSpeechToText($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-speech-to-text/pray");

        $data["bookCode"] = "2ti";
        $data["bookName"] = "2 Timothy";
        $data["targetLangName"] = "Papua Malay";
        $data["inputMode"] = InputMode::SPEECH_TO_TEXT;
        $data["currentChapter"] = 2;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "id";

        $notifications = DemoData::getScriptureNotifications(
            "ulb",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"],
            "l1",
            "ulb",
            $data["inputMode"]
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["comments"] = DemoData::getHelpComments();

        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["next_step"] = EventSteps::PRAY;

        $view = View::make("Events/L1/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/L1/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = "multi-draft_input_mode";
                break;

            case "input":
                $view->nest("page", "Events/L1/Demo/Input");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/L1/Demo/SelfCheckInputMode");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW;
                break;

            case "peer_review":
                $view->nest("page", "Events/L1/Demo/PeerReviewInputMode");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/L1/Demo/CheckerPeerReviewInputMode");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "information":
                return View::make("Events/L1/Demo/Information")
                    ->shares("title", __("event_info"))
                    ->shares("data", $data);
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoTn($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-tn/pray");

        $data["bookCode"] = "act";
        $data["bookName"] = "Acts";
        $data["targetLangName"] = "Bahasa Indonesia";
        $notifications = DemoData::getHelpNotifications(
            "tn",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            1
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["comments"] = DemoData::getHelpComments();

        $view = View::make("Events/Notes/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Notes/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME . "_tn";
                break;

            case "consume":
                $view->nest("page", "Events/Notes/Demo/Consume");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::READ_CHUNK . "_tn";
                break;

            case "read_chunk":
                $view->nest("page", "Events/Notes/Demo/ReadChunk");
                $data["step"] = EventSteps::READ_CHUNK;
                $data["next_step"] = EventSteps::BLIND_DRAFT;
                break;

            case "blind_draft":
                $view->nest("page", "Events/Notes/Demo/BlindDraft");
                $data["step"] = EventSteps::BLIND_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/Notes/Demo/SelfEdit");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "pray_chk":
                $view->nest("page", "Events/Notes/Demo/PrayChk");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME . "_tn";
                $data["isCheckerPage"] = true;
                break;

            case "consume_chk":
                $view->nest("page", "Events/Notes/Demo/ConsumeChk");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::HIGHLIGHT . "_tn";
                $data["isCheckerPage"] = true;
                break;

            case "highlight":
                $view->nest("page", "Events/Notes/Demo/Highlight");
                $data["step"] = EventSteps::HIGHLIGHT;
                $data["next_step"] = EventSteps::SELF_CHECK . "_tn_chk";
                $data["isCheckerPage"] = true;
                break;

            case "self_check_chk":
                $view->nest("page", "Events/Notes/Demo/SelfEditChk");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = EventSteps::KEYWORD_CHECK . "_tn";
                $data["isCheckerPage"] = true;
                break;

            case "highlight_chk":
                $view->nest("page", "Events/Notes/Demo/HighlightChk");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW . "_tn";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review":
                $view->nest("page", "Events/Notes/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/Notes/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                $data["isPeerPage"] = true;
                break;

            case "information":
                return View::make("Events/Notes/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoTq($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-tq/pray");

        $data["bookCode"] = "3jn";
        $data["bookName"] = "3 John";
        $data["targetLangName"] = "Русский";
        $notifications = DemoData::getHelpNotifications(
            "tq",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            1
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["comments"] = DemoData::getHelpComments();

        $view = View::make("Events/Questions/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Questions/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::MULTI_DRAFT;
                break;

            case "multi_draft":
                $view->nest("page", "Events/Questions/Demo/MultiDraft");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/Questions/Demo/SelfEdit");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "pray_chk":
                $view->nest("page", "Events/Questions/Demo/PrayChk");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::KEYWORD_CHECK;
                $data["isCheckerPage"] = true;
                break;

            case "keyword_check":
                $view->nest("page", "Events/Questions/Demo/KeywordCheck");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW . "_tq";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review":
                $view->nest("page", "Events/Questions/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/Questions/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                $data["isPeerPage"] = true;
                break;

            case "information":
                return View::make("Events/Questions/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoTw($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-tw/pray");

        $data["bookCode"] = "wns";
        $data["bookName"] = "names";
        $data["targetLangName"] = "Русский";
        $notifications = DemoData::getHelpNotifications(
            "tw",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            "aaron...adam"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["comments"] = DemoData::getHelpComments();

        $view = View::make("Events/TWords/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/TWords/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::MULTI_DRAFT;
                break;

            case "multi_draft":
                $view->nest("page", "Events/TWords/Demo/MultiDraft");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/TWords/Demo/SelfEdit");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "pray_chk":
                $view->nest("page", "Events/TWords/Demo/PrayChk");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::KEYWORD_CHECK;
                $data["isCheckerPage"] = true;
                break;

            case "keyword_check":
                $view->nest("page", "Events/TWords/Demo/KeywordCheck");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW . "_tw";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review":
                $view->nest("page", "Events/TWords/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/TWords/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                $data["isPeerPage"] = true;
                break;

            case "information":
                return View::make("Events/TWords/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoRevision($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-revision/pray");

        $data["bookCode"] = "2ti";
        $data["bookName"] = "2 Timothy";
        $data["targetLangName"] = "English";
        $data["currentChapter"] = 2;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "id";

        $notifications = DemoData::getScriptureNotifications(
            "ulb",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"],
            "l2"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = true;
        $data["next_step"] = EventCheckSteps::PRAY;
        $data["comments"] = DemoData::getRevisionComments();

        $view = View::make("Events/Revision/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Revision/Demo/Pray");
                $data["step"] = EventCheckSteps::PRAY;
                $data["next_step"] = EventCheckSteps::CONSUME;
                break;

            case "consume":
                $view->nest("page", "Events/Revision/Demo/Consume");
                $data["step"] = EventCheckSteps::CONSUME;
                $data["next_step"] = EventCheckSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/Revision/Demo/SelfCheck");
                $data["step"] = EventCheckSteps::SELF_CHECK;
                $data["next_step"] = EventCheckSteps::PEER_REVIEW;
                break;

            case "peer_review":
                $view->nest("page", "Events/Revision/Demo/PeerReview");
                $data["step"] = EventCheckSteps::PEER_REVIEW;
                $data["next_step"] = EventCheckSteps::KEYWORD_CHECK;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/Revision/Demo/PeerReviewChecker");
                $data["step"] = EventCheckSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                unset($data["isCheckerPage"]);
                break;

            case "keyword_check":
                $view->nest("page", "Events/Revision/Demo/KeywordCheck");
                $data["step"] = EventCheckSteps::KEYWORD_CHECK;
                $data["next_step"] = EventCheckSteps::CONTENT_REVIEW;
                break;

            case "keyword_check_checker":
                $view->nest("page", "Events/Revision/Demo/KeywordCheckChecker");
                $data["step"] = EventCheckSteps::KEYWORD_CHECK;
                $data["next_step"] = "continue_alt";
                unset($data["isCheckerPage"]);
                break;

            case "content_review":
                $view->nest("page", "Events/Revision/Demo/ContentReview");
                $data["step"] = EventCheckSteps::CONTENT_REVIEW;
                $data["next_step"] = "continue_alt";
                break;

            case "content_review_checker":
                $view->nest("page", "Events/Revision/Demo/ContentReviewChecker");
                $data["step"] = EventCheckSteps::CONTENT_REVIEW;
                $data["next_step"] = "continue_alt";
                unset($data["isCheckerPage"]);
                break;

            case "information":
                return View::make("Events/Revision/Demo/Information")
                    ->shares("title", __("event_info"));
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoReview($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-review/pray");

        $data["bookCode"] = "2ti";
        $data["bookName"] = "2 Timothy";
        $data["targetLangName"] = "Papuan Malay";
        $data["currentChapter"] = 2;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "id";

        $notifications = DemoData::getScriptureNotifications(
            "ulb",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"],
            "l3"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = true;
        $data["isPeer"] = false;
        $data["next_step"] = EventCheckSteps::PRAY;
        $data["comments"] = DemoData::getReviewComments();

        $view = View::make("Events/Review/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Review/Demo/Pray");
                $data["step"] = EventCheckSteps::PRAY;
                $data["next_step"] = EventCheckSteps::PEER_REVIEW_L3;
                break;

            case "peer_review_l3":
                $view->nest("page", "Events/Review/Demo/PeerReview");
                $data["step"] = EventCheckSteps::PEER_REVIEW_L3;
                $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;
                break;

            case "peer_edit_l3":
                $view->nest("page", "Events/Review/Demo/PeerEdit");
                $data["step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["next_step"] = "continue_alt";
                break;

            case "peer_review_l3_checker":
                $view->nest("page", "Events/Review/Demo/PeerReviewChecker");
                $data["step"] = EventCheckSteps::PEER_REVIEW_L3;
                $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["isPeer"] = true;
                break;

            case "peer_edit_l3_checker":
                $view->nest("page", "Events/Review/Demo/PeerEditChecker");
                $data["step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["next_step"] = "continue_alt";
                $data["isPeer"] = true;
                break;

            case "information":
                return View::make("Events/Review/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoReviewNotes($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-tn-review/pray");

        $data["bookCode"] = "mrk";
        $data["bookName"] = "Mark";
        $data["targetLangName"] = "Bahasa Indonesia";
        $data["currentChapter"] = 16;
        $data["tnLangID"] = "en";

        $notifications = DemoData::getScriptureNotifications(
            "tn",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"],
            "l3"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = true;
        $data["isPeer"] = false;
        $data["next_step"] = EventCheckSteps::PRAY;

        $view = View::make("Events/ReviewNotes/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/ReviewNotes/Demo/Pray");
                $data["step"] = EventCheckSteps::PRAY;
                $data["next_step"] = EventCheckSteps::PEER_REVIEW_L3;
                break;

            case "peer_review_l3":
                $view->nest("page", "Events/ReviewNotes/Demo/PeerReview");
                $data["step"] = EventCheckSteps::PEER_REVIEW_L3;
                $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;
                break;

            case "peer_edit_l3":
                $view->nest("page", "Events/ReviewNotes/Demo/PeerEdit");
                $data["step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["next_step"] = "continue_alt";
                break;

            case "peer_review_l3_checker":
                $view->nest("page", "Events/ReviewNotes/Demo/PeerReviewChecker");
                $data["step"] = EventCheckSteps::PEER_REVIEW_L3;
                $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["isPeer"] = true;
                break;

            case "peer_edit_l3_checker":
                $view->nest("page", "Events/ReviewNotes/Demo/PeerEditChecker");
                $data["step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["next_step"] = "continue_alt";
                $data["isPeer"] = true;
                break;

            case "information":
                return View::make("Events/ReviewNotes/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoSun($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-sun/pray");

        $data["bookCode"] = "mat";
        $data["bookName"] = "Matthew";
        $data["targetLangName"] = "English";
        $data["currentChapter"] = 17;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 27;
        $data["targetLang"] = "en-x-demo1";

        $notifications = DemoData::getScriptureNotifications(
            "sun",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"]
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["isCheckerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["menu"] = 5;
        $data["comments"] = DemoData::getComments();

        $this->_saildictModel = new SailDictionaryModel();

        $view = View::make("Events/SUN/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/SUN/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME;
                break;

            case "consume":
                $view->nest("page", "Events/SUN/Demo/Consume");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::CHUNKING . "_sun";
                break;

            case "chunking":
                $view->nest("page", "Events/SUN/Demo/Chunking");
                $data["step"] = EventSteps::CHUNKING;
                $data["next_step"] = EventSteps::REARRANGE;
                break;

            case "rearrange":
                $view->nest("page", "Events/SUN/Demo/WordsDraft");
                $data["step"] = EventSteps::REARRANGE;
                $data["next_step"] = EventSteps::SYMBOL_DRAFT;
                break;

            case "symbol-draft":
                $view->nest("page", "Events/SUN/Demo/SymbolsDraft");
                $data["step"] = EventSteps::SYMBOL_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self-check":
                $view->nest("page", "Events/SUN/Demo/SelfCheck");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "theo_check_checker":
                $view->nest("page", "Events/SUN/Demo/TheoCheck");
                $data["step"] = EventSteps::THEO_CHECK;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "content_review_checker":
                $view->nest("page", "Events/SUN/Demo/ContentReview");
                $data["step"] = EventSteps::CONTENT_REVIEW;
                $data["next_step"] = EventSteps::FINAL_REVIEW;
                $data["isCheckerPage"] = true;
                break;

            case "verse-markers":
                $view->nest("page", "Events/SUN/Demo/FinalReview");
                $data["step"] = EventSteps::FINAL_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "information":
                return View::make("Events/SUN/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoSunRevision($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-sun-revision/pray");

        $data["bookCode"] = "2ti";
        $data["bookName"] = "2 Timothy";
        $data["targetLangName"] = "English";
        $data["currentChapter"] = 2;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "en-x-demo1";

        $notifications = DemoData::getScriptureNotifications(
            "sun",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"],
            "l2"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = true;
        $data["next_step"] = EventCheckSteps::PRAY;
        $data["comments"] = DemoData::getRevisionComments();

        $view = View::make("Events/RevisionSun/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/RevisionSun/Demo/Pray");
                $data["step"] = EventCheckSteps::PRAY;
                $data["next_step"] = EventCheckSteps::CONSUME;
                break;

            case "consume":
                $view->nest("page", "Events/RevisionSun/Demo/Consume");
                $data["step"] = EventCheckSteps::CONSUME;
                $data["next_step"] = EventCheckSteps::SELF_CHECK . "_sun";
                break;

            case "peer_check":
                $view->nest("page", "Events/RevisionSun/Demo/PeerCheck");
                $data["step"] = EventCheckSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "theo_check":
                $view->nest("page", "Events/RevisionSun/Demo/TheoCheck");
                $data["step"] = EventCheckSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                break;

            case "information":
                return View::make("Events/RevisionSun/Demo/Information")
                    ->shares("title", __("event_info"));
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoSunReview($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-sun-review/pray");

        $data["bookCode"] = "mat";
        $data["bookName"] = "Matthew";
        $data["targetLangName"] = "English";
        $data["currentChapter"] = 17;
        $data["tnLangID"] = "en";
        $data["tqLangID"] = "en";
        $data["twLangID"] = "en";
        $data["bcLangID"] = "en";
        $data["totalVerses"] = 26;
        $data["targetLang"] = "en-x-demo1";

        $notifications = DemoData::getScriptureNotifications(
            "sun",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            $data["currentChapter"],
            "l3"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["comments"] = DemoData::getReviewComments();

        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = true;
        $data["isPeer"] = false;
        $data["next_step"] = EventCheckSteps::PRAY;

        $view = View::make("Events/ReviewSun/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/ReviewSun/Demo/Pray");
                $data["step"] = EventCheckSteps::PRAY;
                $data["next_step"] = EventCheckSteps::PEER_REVIEW_L3;
                break;

            case "peer_review_l3":
                $view->nest("page", "Events/ReviewSun/Demo/PeerReview");
                $data["step"] = EventCheckSteps::PEER_REVIEW_L3;
                $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;
                break;

            case "peer_edit_l3":
                $view->nest("page", "Events/ReviewSun/Demo/PeerEdit");
                $data["step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["next_step"] = "continue_alt";
                break;

            case "peer_review_l3_checker":
                $view->nest("page", "Events/ReviewSun/Demo/PeerReviewChecker");
                $data["step"] = EventCheckSteps::PEER_REVIEW_L3;
                $data["next_step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["isPeer"] = true;
                break;

            case "peer_edit_l3_checker":
                $view->nest("page", "Events/ReviewSun/Demo/PeerEditChecker");
                $data["step"] = EventCheckSteps::PEER_EDIT_L3;
                $data["next_step"] = "continue_alt";
                $data["isPeer"] = true;
                break;

            case "information":
                return View::make("Events/ReviewSun/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoSunOdb($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-sun-odb/pray");

        $data["bookCode"] = "a01";
        $data["bookName"] = "A01";
        $data["targetLangName"] = "English";
        $notifications = DemoData::getScriptureNotifications(
            "sun",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            2,
            "l1",
            "odb"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["isCheckerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["menu"] = 5;
        $data["comments"] = DemoData::getComments();

        $this->_saildictModel = new SailDictionaryModel();

        $view = View::make("Events/ODBSUN/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/ODBSUN/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME . "_odb";
                break;

            case "consume":
                $view->nest("page", "Events/ODBSUN/Demo/Consume");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::REARRANGE;
                break;

            case "rearrange":
                $view->nest("page", "Events/ODBSUN/Demo/WordsDraft");
                $data["step"] = EventSteps::REARRANGE;
                $data["next_step"] = EventSteps::SYMBOL_DRAFT;
                break;

            case "symbol-draft":
                $view->nest("page", "Events/ODBSUN/Demo/SymbolsDraft");
                $data["step"] = EventSteps::SYMBOL_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self-check":
                $view->nest("page", "Events/ODBSUN/Demo/SelfCheck");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "theo_check_checker":
                $view->nest("page", "Events/ODBSUN/Demo/TheoCheck");
                $data["step"] = EventSteps::THEO_CHECK;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "content_review_checker":
                $view->nest("page", "Events/ODBSUN/Demo/ContentReview");
                $data["step"] = EventSteps::CONTENT_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "information":
                return View::make("Events/ODBSUN/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoRadio($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-rad/pray");

        $data["bookCode"] = "b02";
        $data["bookName"] = "B06";
        $data["targetLangName"] = "Español Latin America";
        $notifications = DemoData::getScriptureNotifications(
            "rad",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            1,
            "l1",
            "rad"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["isCheckerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["menu"] = 5;
        $data["comments"] = DemoData::getComments();

        $view = View::make("Events/Radio/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Radio/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME . "_odb";
                break;

            case "consume":
                $view->nest("page", "Events/Radio/Demo/Consume");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::MULTI_DRAFT;
                break;

            case "multi-draft":
                $view->nest("page", "Events/Radio/Demo/MultiDraft");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self-check":
                $view->nest("page", "Events/Radio/Demo/SelfCheck");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "peer_review":
                $view->nest("page", "Events/Radio/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "information":
                return View::make("Events/Radio/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoObs($page = null) {
        $isSun = isset($_COOKIE["sun_mode"]);

        if (!isset($page))
            Url::redirect("events/demo-obs/pray");

        $data["sourceLang"] = "en";
        $data["sourceLangDir"] = "ltr";
        $data["targetLangDir"] = "ltr";
        $data["bookCode"] = "obs";
        $data["bookName"] = __("obs");

        if ($isSun) {
            $data["targetLang"] = "sgn-US-symbunot";
            $data["targetLangName"] = "Symbolic Universal Notation";
        } else {
            $data["targetLang"] = "ru";
            $data["targetLangName"] = "Русский";
        }

        $data["source_text"] = DemoData::getObsSourceText($data["sourceLang"]);
        $data["target_text"] = DemoData::getObsTargetText($data["targetLang"]);

        $data["comments"] = DemoData::getHelpComments();
        $notifications = DemoData::getHelpNotifications(
            "obs",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            4
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;

        $view = View::make("Events/Obs/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Obs/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::CONSUME;
                break;

            case "consume":
                $view->nest("page", "Events/Obs/Demo/Consume");
                $data["step"] = EventSteps::CONSUME;
                $data["next_step"] = EventSteps::BLIND_DRAFT;
                break;

            case "blind_draft":
                $view->nest("page", "Events/Obs/Demo/BlindDraft");
                $data["step"] = EventSteps::BLIND_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/Obs/Demo/SelfEdit");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "pray_chk":
                $view->nest("page", "Events/Obs/Demo/PrayChk");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::KEYWORD_CHECK;
                $data["isCheckerPage"] = true;
                break;

            case "keyword_check":
                $view->nest("page", "Events/Obs/Demo/KeywordCheck");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW . "_obs";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review":
                $view->nest("page", "Events/Obs/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/Obs/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                $data["isPeerPage"] = true;
                break;

            case "information":
                return View::make("Events/Obs/Demo/Information")
                    ->shares("title", __("event_info"))
                    ->shares("data", $data);
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoBc($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-bc/pray");

        $data["bookCode"] = "mat";
        $data["bookName"] = "Matthew";
        $data["targetLangName"] = "Español";
        $notifications = DemoData::getHelpNotifications(
            "bc",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            4
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["comments"] = DemoData::getHelpComments();

        $view = View::make("Events/Bc/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Bc/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::MULTI_DRAFT;
                break;

            case "multi_draft":
                $view->nest("page", "Events/Bc/Demo/MultiDraft");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/Bc/Demo/SelfEdit");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "pray_chk":
                $view->nest("page", "Events/Bc/Demo/PrayChk");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::KEYWORD_CHECK;
                $data["isCheckerPage"] = true;
                break;

            case "keyword_check":
                $view->nest("page", "Events/Bc/Demo/KeywordCheck");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW . "_bca";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review":
                $view->nest("page", "Events/Bc/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/Bc/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                $data["isPeerPage"] = true;
                break;

            case "information":
                return View::make("Events/Bc/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }

    public function demoBca($page = null)
    {
        if (!isset($page))
            Url::redirect("events/demo-bca/pray");

        $data["bookCode"] = "mat";
        $data["bookName"] = "Matthew";
        $data["targetLangName"] = "Español";
        $notifications = DemoData::getHelpNotifications(
            "bca",
            $data["bookCode"],
            $data["bookName"],
            $data["targetLangName"],
            "messiahchrist"
        );

        $this->renderNotifications->setNotifications($notifications);
        $data["notifications"] = $this->renderNotifications->renderDemo();
        $data["isDemo"] = true;
        $data["menu"] = 5;
        $data["isCheckerPage"] = false;
        $data["isPeerPage"] = false;
        $data["next_step"] = EventSteps::PRAY;
        $data["comments"] = DemoData::getHelpComments();

        $view = View::make("Events/Bca/Demo/DemoHeader");
        $data["step"] = "";

        switch ($page) {
            case "pray":
                $view->nest("page", "Events/Bca/Demo/Pray");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::MULTI_DRAFT;
                break;

            case "multi_draft":
                $view->nest("page", "Events/Bca/Demo/MultiDraft");
                $data["step"] = EventSteps::MULTI_DRAFT;
                $data["next_step"] = EventSteps::SELF_CHECK;
                break;

            case "self_check":
                $view->nest("page", "Events/Bca/Demo/SelfEdit");
                $data["step"] = EventSteps::SELF_CHECK;
                $data["next_step"] = "continue_alt";
                break;

            case "pray_chk":
                $view->nest("page", "Events/Bca/Demo/PrayChk");
                $data["step"] = EventSteps::PRAY;
                $data["next_step"] = EventSteps::KEYWORD_CHECK;
                $data["isCheckerPage"] = true;
                break;

            case "keyword_check":
                $view->nest("page", "Events/Bca/Demo/KeywordCheck");
                $data["step"] = EventSteps::KEYWORD_CHECK;
                $data["next_step"] = EventSteps::PEER_REVIEW . "_bca";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review":
                $view->nest("page", "Events/Bca/Demo/PeerReview");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                break;

            case "peer_review_checker":
                $view->nest("page", "Events/Bca/Demo/PeerReviewChecker");
                $data["step"] = EventSteps::PEER_REVIEW;
                $data["next_step"] = "continue_alt";
                $data["isCheckerPage"] = true;
                $data["isPeerPage"] = true;
                break;

            case "information":
                return View::make("Events/Bca/Demo/Information")
                    ->shares("title", __("event_info"));
                break;
        }

        return $view
            ->shares("title", __("demo"))
            ->shares("data", $data);
    }
}
