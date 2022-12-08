<?php


namespace App\Domain;


use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\StepsStates;

class AnyL3Progress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];
        $startChapter = $event->project->bookProject == "tn" ? 0 : 1;
        for ($i = $startChapter; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        foreach ($event->chapters as $chapter) {
            $tmp["l3chID"] = $chapter->l3chID;
            $tmp["l3memberID"] = $chapter->l3memberID;
            $tmp["l3checked"] = $chapter->l3checked;

            if ($chapter->checkerL3) {

                $tmp["currentChapter"] = $chapter->checkerL3->currentChapter;
                $tmp["step"] = $chapter->checkerL3->step;
                $tmp["peerCheck"] = (array)json_decode($chapter->checkerL3->peerCheck, true);
            }

            $data["chapters"][$chapter->chapter] = $tmp;
        }

        $overallProgress = 0;
        $members = [];

        foreach ($data["chapters"] as $key => $chapter) {
            if (empty($chapter)) continue;
            if ($chapter["l3memberID"] == 0) continue;

            $p = !empty($chapter["peerCheck"])
                && array_key_exists($key, $chapter["peerCheck"])
                && $chapter["peerCheck"][$key]["memberID"] > 0;

            $members[$chapter["l3memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            // Set default values
            $data["chapters"][$key]["peerReview"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peerEdit"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peerChk"]["checkerID"] = 'na';

            $currentChapter = $chapter["currentChapter"];
            $currentStep = $chapter["step"];

            if ($p) {
                $data["chapters"][$key]["peerChk"]["checkerID"] = $chapter["peerCheck"][$key]["memberID"];
                $members[$chapter["peerCheck"][$key]["memberID"]] = "";

                if ($chapter["peerCheck"][$key]["done"] == 2) {
                    $data["chapters"][$key]["peerReview"]["state"] = StepsStates::FINISHED;

                    if ($chapter["currentChapter"] == $key) {
                        $data["chapters"][$key]["peerEdit"]["state"] = StepsStates::CHECKED;
                    } else {
                        $data["chapters"][$key]["peerEdit"]["state"] = StepsStates::FINISHED;
                    }
                } elseif ($chapter["peerCheck"][$key]["done"] == 1) {
                    if ($currentStep == EventCheckSteps::PEER_REVIEW_L3) {
                        $data["chapters"][$key]["peerReview"]["state"] = StepsStates::CHECKED;
                    } else {
                        $data["chapters"][$key]["peerReview"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["peerEdit"]["state"] = StepsStates::IN_PROGRESS;
                    }
                } else {
                    if ($currentStep == EventCheckSteps::PEER_REVIEW_L3) {
                        $data["chapters"][$key]["peerReview"]["state"] = StepsStates::IN_PROGRESS;
                    }
                }
            } else {
                if ($currentChapter == $key && $currentStep == EventCheckSteps::PEER_REVIEW_L3)
                    $data["chapters"][$key]["peerReview"]["state"] = StepsStates::WAITING;
            }

            // Progress checks
            if ($data["chapters"][$key]["peerReview"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["peerReview"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 50;
            if ($data["chapters"][$key]["peerEdit"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["peerEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 50;

            $overallProgress += $data["chapters"][$key]["progress"];
        }

        $data["overall_progress"] = $overallProgress / sizeof($data["chapters"]);
        $data["members"] = $members;

        if ($progressOnly) {
            return $data["overall_progress"];
        } else {
            return $data;
        }
    }
}