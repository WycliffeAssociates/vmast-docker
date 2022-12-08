<?php


namespace App\Domain;


use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;

class TqProgress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];
        for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        $overallProgress = 0;
        $members = [];
        $memberSteps = [];

        foreach ($event->chapters as $chapter) {
            $tmp["trID"] = $chapter->trID;
            $tmp["memberID"] = $chapter->memberID;
            $tmp["chunks"] = json_decode($chapter->chunks, true);
            $tmp["done"] = $chapter->done;

            $data["chapters"][$chapter->chapter] = $tmp;

            $translator = $chapter->translator;
            if (!array_key_exists($translator->memberID, $memberSteps)) {
                $memberSteps[$translator->memberID]["step"] = $translator->step;
                $memberSteps[$translator->memberID]["otherCheck"] = $translator->otherCheck;
                $memberSteps[$translator->memberID]["peerCheck"] = $translator->peerCheck;
                $memberSteps[$translator->memberID]["currentChapter"] = $translator->currentChapter;
                $members[$translator->memberID] = "";
            }
        }

        foreach ($event->chunks as $chunk) {
            $data["chapters"][$chunk->chapter]["chunksData"][] = $chunk;

            if (!isset($data["chapters"][$chunk->chapter]["lastEdit"])) {
                $data["chapters"][$chunk->chapter]["lastEdit"] = $chunk->dateUpdate;
            } else {
                $prevDate = strtotime($data["chapters"][$chunk->chapter]["lastEdit"]);
                if ($prevDate < strtotime($chunk->dateUpdate))
                    $data["chapters"][$chunk->chapter]["lastEdit"] = $chunk->dateUpdate;
            }
        }

        foreach ($data["chapters"] as $key => $chapter) {
            if (empty($chapter)) continue;

            $currentStep = EventSteps::PRAY;
            $multiState = StepsStates::NOT_STARTED;

            $members[$chapter["memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            $currentChapter = $memberSteps[$chapter["memberID"]]["currentChapter"];
            $otherCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["otherCheck"], true);
            $peerCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["peerCheck"], true);

            // Set default values
            $data["chapters"][$key]["multi"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;

            $data["chapters"][$key]["kwc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peerChk"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peerChk"]["checkerID"] = 'na';
            $data["chapters"][$key]["stepChk"] = EventSteps::PRAY;

            // When no chunks created or translation not started
            if (empty($chapter["chunks"]) || !isset($chapter["chunksData"])) {
                if ($currentChapter == $key) {
                    $currentStep = $memberSteps[$chapter["memberID"]]["step"];

                    if ($currentStep == EventSteps::MULTI_DRAFT) {
                        $multiState = StepsStates::IN_PROGRESS;
                    }
                }

                $data["chapters"][$key]["step"] = $currentStep;
                $data["chapters"][$key]["multi"]["state"] = $multiState;

                // Progress checks
                if ($data["chapters"][$key]["multi"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 25;

                $data["chapters"][$key]["chunksData"] = [];
                continue;
            }

            $currentStep = $memberSteps[$chapter["memberID"]]["step"];

            $data["chapters"][$key]["step"] = $currentChapter == $key ? $currentStep : EventSteps::FINISHED;

            if ($currentChapter == $key) {
                if ($currentStep == EventSteps::MULTI_DRAFT) {
                    $data["chapters"][$key]["multi"]["state"] = StepsStates::IN_PROGRESS;
                } else if ($currentStep == EventSteps::SELF_CHECK) {
                    $data["chapters"][$key]["multi"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            }

            // Checking stage
            if (array_key_exists($key, $otherCheck)) {
                $data["chapters"][$key]["multi"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["step"] = EventSteps::FINISHED;

                if ($otherCheck[$key]["memberID"] > 0) {
                    $members[$otherCheck[$key]["memberID"]] = "";
                    $data["chapters"][$key]["checkerID"] = $otherCheck[$key]["memberID"];

                    if ($otherCheck[$key]["done"] == 3) {
                        $members[$peerCheck[$key]["memberID"]] = "";
                        $data["chapters"][$key]["kwc"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["peerChk"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["peerChk"]["checkerID"] = $peerCheck[$key]["memberID"];
                        $data["chapters"][$key]["stepChk"] = EventSteps::FINISHED;
                    } else {
                        switch ($otherCheck[$key]["done"]) {
                            case 1:
                                $data["chapters"][$key]["kwc"]["state"] = StepsStates::IN_PROGRESS;
                                break;
                            case 2:
                                $data["chapters"][$key]["kwc"]["state"] = StepsStates::FINISHED;

                                if (array_key_exists($key, $peerCheck) && $peerCheck[$key]["done"]) {
                                    $data["chapters"][$key]["peerChk"]["state"] = StepsStates::CHECKED;
                                    $data["chapters"][$key]["peerChk"]["checkerID"] = $peerCheck[$key]["memberID"];
                                    $members[$peerCheck[$key]["memberID"]] = "";
                                } else if (!array_key_exists($key, $peerCheck) || $peerCheck[$key]["memberID"] == 0) {
                                    $data["chapters"][$key]["peerChk"]["state"] = StepsStates::WAITING;
                                } else {
                                    $data["chapters"][$key]["peerChk"]["state"] = StepsStates::IN_PROGRESS;
                                    $data["chapters"][$key]["peerChk"]["checkerID"] = $peerCheck[$key]["memberID"];
                                    $members[$peerCheck[$key]["memberID"]] = "";
                                }
                                break;
                        }
                    }
                } else {
                    $data["chapters"][$key]["kwc"]["state"] = StepsStates::WAITING;
                }
            } else {
                if ($key == $currentChapter) {
                    if ($currentStep == EventSteps::SELF_CHECK) {
                        $data["chapters"][$key]["multi"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                    }
                }
            }

            // Progress checks
            if ($data["chapters"][$key]["multi"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["peerChk"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += 12;
            if ($data["chapters"][$key]["peerChk"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;

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