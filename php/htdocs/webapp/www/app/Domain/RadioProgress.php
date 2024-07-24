<?php


namespace App\Domain;


use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;

class RadioProgress
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
            $consumeState = StepsStates::NOT_STARTED;
            $multiDraftState = StepsStates::NOT_STARTED;

            $members[$chapter["memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            $currentChapter = $memberSteps[$chapter["memberID"]]["currentChapter"];
            $peerCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["peerCheck"], true);

            // Set default values
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;

            $data["chapters"][$key]["peerChk"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["stepChk"] = EventSteps::PRAY;

            // When no chunks created or translation not started
            if (empty($chapter["chunks"]) || !isset($chapter["chunksData"])) {
                if ($currentChapter == $key) {
                    $currentStep = $memberSteps[$chapter["memberID"]]["step"];

                    if ($currentStep == EventSteps::CONSUME) {
                        $consumeState = StepsStates::IN_PROGRESS;
                    } else if ($currentStep == EventSteps::MULTI_DRAFT) {
                        $consumeState = StepsStates::FINISHED;
                        $multiDraftState = StepsStates::IN_PROGRESS;
                    }
                }

                $data["chapters"][$key]["step"] = $currentStep;
                $data["chapters"][$key]["consume"]["state"] = $consumeState;
                $data["chapters"][$key]["multiDraft"]["state"] = $multiDraftState;

                // Progress checks
                if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 25;

                $data["chapters"][$key]["chunksData"] = [];
                $overallProgress += $data["chapters"][$key]["progress"];
                continue;
            }

            $currentStep = $memberSteps[$chapter["memberID"]]["step"];

            // These steps are finished here by default
            $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;

            if ($currentChapter == $key) {
                if ($currentStep == EventSteps::MULTI_DRAFT) {
                    $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::IN_PROGRESS;
                } else if ($currentStep == EventSteps::SELF_CHECK) {
                    $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            }

            // Radio Checking stage
            if (array_key_exists($key, $peerCheck)) {
                $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["step"] = EventSteps::FINISHED;

                if ($peerCheck[$key]["memberID"] > 0) {
                    $members[$peerCheck[$key]["memberID"]] = "";
                    $data["chapters"][$key]["checkerID"] = $peerCheck[$key]["memberID"];

                    if ($peerCheck[$key]["done"] == 0) {
                        $data["chapters"][$key]["peerChk"]["state"] = StepsStates::IN_PROGRESS;
                    } else {
                        $data["chapters"][$key]["peerChk"]["state"] = StepsStates::FINISHED;
                    }
                }
            }

            // Progress checks
            if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["multiDraft"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;
            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 25;
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