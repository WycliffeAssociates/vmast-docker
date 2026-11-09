<?php

namespace App\Data;

use Helpers\Constants\EventSteps;
use Helpers\Constants\NotificationType;

class Notification {
    private $noteID;
    private $mode;
    private $name;
    private $step;
    private $chapter;
    private $project;
    private $level;
    private $anchor;
    private $isHelp;
    private $isHelpMode;
    private $isSun;
    private $isSunMode;
    private $isRadioMode;
    private $isInputMode;
    private $isScriptureMode;
    private $isRevisionMode;
    private $isReviewMode;
    private $type;
    private $notification;
    private $manageMode;

    public function __construct($notification) {
        $this->notification = $notification;
        $this->noteID = $notification->noteID ?? null;
        $this->manageMode = $notification->manageMode ?? null;

        $helpTools = ["tn","tq","tw","obs","bc","bca"];

        $this->isHelp = in_array($notification->bookProject, $helpTools);
        $this->isHelpMode = in_array($this->manageMode, $helpTools);
        $this->isScriptureMode = !$this->manageMode && in_array($this->notification->bookProject, ["ulb","udb"]);
        $this->isSun = $notification->bookProject == "sun";
        $this->isSunMode = $this->manageMode == "sun";
        $this->isRadioMode = $this->manageMode == "rad";
        $this->isInputMode = isset($notification->inputMode);
        $this->isRevisionMode = $this->manageMode == "l2";
        $this->isReviewMode = $this->manageMode == "l3";

        $this->setMode();
        $this->setName();
        $this->setStep();
        $this->setChapter();
        $this->setProject();
        $this->setLevel();
        $this->setAnchor();
        $this->setType();
    }

    public function getText(): string {
        $chapter_data = [
            "step" => "<b>".$this->step."</b>",
            "book" => "<b>".$this->notification->bookName."</b>",
            "chapter" => "<b>".$this->chapter."</b>",
            "language" => "<b>".$this->notification->tLang."</b>",
            "project" => "<b>".$this->project,
            "level" => $this->level."</b>"
        ];

        $tail = null;

        if ($this->isHelp && !$this->isReviewMode) {
            $tail = " (".($this->notification->step == "other" ? "#1" : "#2").")";
        } elseif ($this->isScriptureMode && $this->notification->step == EventSteps::CONTENT_REVIEW) {
            if (isset($this->notification->vChecker)) {
                $tail = " (".($this->notification->vChecker == 1 ? __("l1_v1_checker") : __("l1_v2_checker")).")";
            }
        }

        switch ($this->notification->bookProject) {
            case "tw":
                $chapter_info = __('apply_for_group', $chapter_data);
                break;
            case "bca":
                $chapter_info = __('apply_for_word', $chapter_data);
                break;
            default:
                $chapter_info = __('apply_for_chapter', $chapter_data);
        }

        switch ($this->type) {
            case NotificationType::STARTED:
                $key = "checker_apply";
                break;
            case NotificationType::READY:
                $key = "checker_ready";
                break;
            case NotificationType::DONE:
                $key = "checker_approved";
                break;
            default:
                $key = "checker_apply";
        }

        $text = __($key, ["name" => "<b>".$this->name."</b>"]) . " " . $chapter_info;

        return $text . ($tail ?? "");
    }

    public function getUrl(): string {
        if ($this->noteID) {
            $url = "/events/notifications/$this->noteID/apply";
        } else {
            $url = "/events/checker";
            if ($this->isHelpMode || $this->isRadioMode || $this->isSunMode) {
                $url .= "-".$this->manageMode;
            }
            $url .= "/".$this->notification->eventID."/";
            $url .= $this->notification->memberID."/";
            if ($this->isScriptureMode) {
                $url .= $this->notification->currentChapter."/";
            }
            $url .= $this->notification->step."/";
            if ($this->manageMode) {
                $url .= $this->notification->currentChapter."/";
            }
            if (!$this->isRevisionMode && !$this->isReviewMode && !$this->isSunMode && $this->isScriptureMode) {
                $url .= "v".($this->notification->vChecker ?? 1)."/";
            }
            switch ($this->type) {
                case NotificationType::READY:
                    $url .= "notified";
                    break;
                case NotificationType::DONE:
                    $url .= "approved";
                    break;
                default:
                    $url .= "apply";
            }
        }

        return $url;
    }

    public function getDrafterUrl(): string {
        if ($this->isScriptureMode) {
            $url = "/events/translator";
        } else {
            $url = "/events/checker";
        }
        if ($this->isHelp || $this->isSun) {
            $url .= "-".$this->notification->bookProject;
        }
        if ($this->isRevisionMode) {
            $url .= "-revision";
        }
        if ($this->isReviewMode) {
            $url .= "-review";
        }
        $url .= "/".$this->notification->eventID."/";
        if ($this->isScriptureMode || $this->isRevisionMode) {
            $url .= $this->notification->currentChapter;
        } elseif ($this->isHelpMode) {
            $url .= $this->notification->memberID."/";
            $url .= $this->notification->currentChapter;
        }
        return $url;
    }

    public function getDemoUrl(): string {
        $step = $this->mode;
        $project = "demo";

        if ($this->isRevisionMode) {
            $project .= ($this->notification->bookProject == "sun" ? "-sun" : "")."-revision";
            $step = preg_replace("/_checker/", "", $this->mode);
            $step = $this->notification->bookProject == "sun" ? "theo_check" : $step;
        } elseif ($this->isReviewMode) {
            $specialMode = in_array($this->notification->bookProject, ["sun","tn"]);
            $project .= $specialMode ? "-" . $this->notification->bookProject : "";
            $project .= "-review";
            $step = preg_replace("/_l3_checker/", "", $this->mode);
        } elseif ($this->isHelpMode || $this->isSunMode) {
            $project .= "-" . $this->manageMode;
            if ($this->notification->sourceBible == "odb") {
                $project .= "-odb";
            }
            $step = preg_replace("/other_checker/", "pray_chk", $this->mode);
        } elseif ($this->isRadioMode) {
            $project .= "-rad";
            $step = "peer_review";
        } elseif ($this->isInputMode) {
            $project .= "-" . $this->notification->inputMode;
        }

        return "/events/".$project."/".preg_replace("/-/", "_", $step);
    }

    public function getMode(): string {
        return $this->mode;
    }

    private function setMode() {
        $this->mode = $this->notification->step . "_checker";
    }

    public function getName(): string {
        return $this->name;
    }

    private function setName() {
        $this->name = $this->notification->firstName
            . " "
            . mb_substr($this->notification->lastName, 0, 1)
            . ".";
    }

    public function getStep(): string {
        return $this->step;
    }

    private function setStep() {
        if ($this->notification->step != "other") {
            $step = $this->notification->step;
            if ($this->isHelp && $this->notification->step == EventSteps::PEER_REVIEW) {
                $step .= "_".$this->notification->bookProject;
            } elseif ($this->notification->sourceBible == "odb") {
                $step .= "_odb";
            } elseif ($this->isRevisionMode && $this->notification->bookProject == "sun") {
                $step .= "_sun";
            }
            $step = __($step);
        } else {
            $step = __("level", 2);
        }
        $this->step = $step;
    }

    public function getChapter(): string {
        return $this->chapter;
    }

    private function setChapter() {
        if (isset($this->notification->group)) {
            $this->chapter = $this->notification->group;
        } elseif (isset($this->notification->word)) {
            $this->chapter = $this->notification->word;
        } elseif ($this->notification->currentChapter == 0) {
            $this->chapter = __("intro");
        } else {
            $this->chapter = $this->notification->currentChapter;
        }
    }

    public function getProject(): string {
        return $this->project;
    }

    private function setProject() {
        $this->project = $this->notification->sourceBible == "odb"
            ? __($this->notification->sourceBible)
            : __($this->notification->bookProject);
    }

    public function getLevel(): string {
        return $this->level;
    }

    private function setLevel() {
        $this->level = $this->isRevisionMode || $this->isReviewMode
            ? "(".__($this->manageMode).")"
            : "";
    }

    public function getAnchor(): string {
        return $this->anchor;
    }

    private function setAnchor() {
        $eventID = $this->notification->eventID ?? null;
        $memberID = $this->notification->memberID ?? null;

        $this->anchor = "";

        if ($eventID && $memberID) {
            $eventID = $this->notification->eventID;
            $memberID = $this->notification->memberID;
            $this->anchor = "check:$eventID:$memberID";
        }
    }

    public function getType(): string {
        return $this->type;
    }

    private function setType() {
        $this->type = $this->notification->type ?? NotificationType::STARTED;
    }
}