<?php


namespace App\Domain;


use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\StepsStates;

class SunRevisionProgress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];
        for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        foreach ($event->chapters as $chapter) {
            $tmp["l2chID"] = $chapter->l2chID;
            $tmp["l2memberID"] = $chapter->l2memberID;
            $tmp["l2checked"] = $chapter->l2checked;

            if ($chapter->checkerL2) {
                $tmp["currentChapter"] = $chapter->checkerL2->currentChapter;
                $tmp["step"] = $chapter->checkerL2->step;
                $tmp["peerCheck"] = $chapter->checkerL2->peerCheck ? (array)json_decode($chapter->checkerL2->peerCheck, true) : [];
            }

            $data["chapters"][$chapter->chapter] = $tmp;
        }

        $overallProgress = 0;
        $members = [];

        foreach ($data["chapters"] as $key => $chapter) {
            if (empty($chapter)) continue;
            if ($chapter["l2memberID"] == 0) continue;

            $peerCheck = $chapter["peerCheck"];

            $members[$chapter["l2memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            // Set default values
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peer"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peer"]["checkerID"] = 'na';

            $currentChapter = $chapter["currentChapter"];
            $currentStep = $chapter["step"];

            // Checking stage

            // Peer Check
            if (array_key_exists($key, $peerCheck)) {
                $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["peer"]["state"] = StepsStates::WAITING;
                $data["chapters"][$key]["peer"]["checkerID"] = 0;

                if ($peerCheck[$key]["memberID"] > 0) {
                    $members[$peerCheck[$key]["memberID"]] = "";
                    $data["chapters"][$key]["peer"]["state"] = StepsStates::IN_PROGRESS;
                    $data["chapters"][$key]["peer"]["checkerID"] = $peerCheck[$key]["memberID"];

                    if ($peerCheck[$key]["done"] == 1) {
                        $data["chapters"][$key]["peer"]["state"] = StepsStates::FINISHED;
                    }
                }
            }

            if ($currentChapter == $key) {
                if ($currentStep == EventCheckSteps::CONSUME) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::IN_PROGRESS;
                } else if ($currentStep == EventCheckSteps::SELF_CHECK) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            }

            // Progress checks
            if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 33;
            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 33;
            if ($data["chapters"][$key]["peer"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 34;

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