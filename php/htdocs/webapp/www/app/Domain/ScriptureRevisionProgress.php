<?php


namespace App\Domain;


use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\RevisionMode;
use Helpers\Constants\StepsStates;

class ScriptureRevisionProgress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];
        for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        $fullIncrement = $event->revisionMode == RevisionMode::MAJOR ? 20 : 25;
        $halfIncrement = $event->revisionMode == RevisionMode::MAJOR ? 10 : 12.5;

        foreach ($event->chapters as $chapter) {
            $tmp["l2chID"] = $chapter->l2chID;
            $tmp["l2memberID"] = $chapter->l2memberID;
            $tmp["l2checked"] = $chapter->l2checked;

            if ($chapter->checkerL2) {
                $tmp["currentChapter"] = $chapter->checkerL2->currentChapter;
                $tmp["step"] = $chapter->checkerL2->step;
                $tmp["peerCheck"] = (array)json_decode($chapter->checkerL2->peerCheck, true);
                $tmp["kwCheck"] = (array)json_decode($chapter->checkerL2->kwCheck, true);
                $tmp["crCheck"] = (array)json_decode($chapter->checkerL2->crCheck, true);
            }

            $data["chapters"][$chapter->chapter] = $tmp;
        }

        $overallProgress = 0;
        $members = [];

        foreach ($data["chapters"] as $key => $chapter) {
            if (empty($chapter)) continue;
            if ($chapter["l2memberID"] == 0) continue;

            $peerCheck = $chapter["peerCheck"];
            $kwCheck = $chapter["kwCheck"];
            $crCheck = $chapter["crCheck"];

            $members[$chapter["l2memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            // Set default values
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peer"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peer"]["checkerID"] = 'na';
            $data["chapters"][$key]["kwc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["kwc"]["checkerID"] = 'na';
            $data["chapters"][$key]["crc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["crc"]["checkerID"] = 'na';

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

                    if ($peerCheck[$key]["done"] == 2) {
                        $data["chapters"][$key]["peer"]["state"] = StepsStates::FINISHED;
                    } elseif ($peerCheck[$key]["done"] == 1) {
                        $data["chapters"][$key]["peer"]["state"] = StepsStates::CHECKED;
                    }
                }
            }

            // Keyword Check
            if (array_key_exists($key, $kwCheck)) {
                $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["kwc"]["state"] = StepsStates::WAITING;
                $data["chapters"][$key]["kwc"]["checkerID"] = 0;

                if ($kwCheck[$key]["memberID"] > 0) {
                    $members[$kwCheck[$key]["memberID"]] = "";
                    $data["chapters"][$key]["kwc"]["state"] = StepsStates::IN_PROGRESS;
                    $data["chapters"][$key]["kwc"]["checkerID"] = $kwCheck[$key]["memberID"];

                    if ($kwCheck[$key]["done"] == 2) {
                        $data["chapters"][$key]["kwc"]["state"] = StepsStates::FINISHED;
                    } elseif ($kwCheck[$key]["done"] == 1) {
                        $data["chapters"][$key]["kwc"]["state"] = StepsStates::CHECKED;
                    }
                }
            }

            // Verse-by-Verse Check
            if (array_key_exists($key, $crCheck)) {
                $members[$crCheck[$key]["memberID"]] = "";
                $data["chapters"][$key]["crc"]["state"] = StepsStates::WAITING;
                $data["chapters"][$key]["crc"]["checkerID"] = 0;

                if ($crCheck[$key]["memberID"] > 0) {
                    $data["chapters"][$key]["crc"]["state"] = StepsStates::IN_PROGRESS;
                    $data["chapters"][$key]["crc"]["checkerID"] = $crCheck[$key]["memberID"];

                    if ($crCheck[$key]["done"] == 2) {
                        $data["chapters"][$key]["crc"]["state"] = StepsStates::FINISHED;
                    } elseif ($crCheck[$key]["done"] == 1) {
                        $data["chapters"][$key]["crc"]["state"] = StepsStates::CHECKED;
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
                $data["chapters"][$key]["progress"] += $fullIncrement;
            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $fullIncrement;
            if ($event->revisionMode == RevisionMode::MAJOR) {
                if ($data["chapters"][$key]["peer"]["state"] == StepsStates::CHECKED)
                    $data["chapters"][$key]["progress"] += $halfIncrement;
            }
            if ($data["chapters"][$key]["peer"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $fullIncrement;
            if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += $halfIncrement;
            if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $fullIncrement;
            if ($data["chapters"][$key]["crc"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += $halfIncrement;
            if ($data["chapters"][$key]["crc"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $fullIncrement;

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