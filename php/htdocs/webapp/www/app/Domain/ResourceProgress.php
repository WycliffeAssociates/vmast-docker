<?php


namespace App\Domain;


use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;

class ResourceProgress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];

        $totalChapters = $event->project->bookProject == "bca"
            ? $event->words->count()
            : $event->bookInfo->chaptersNum;

        $firstChapter = $event->project->bookProject == "bc" ? 0 : 1;
        for ($i = $firstChapter; $i <= $totalChapters; $i++) {
            $data["chapters"][$i] = [];
        }

        $overallProgress = 0;
        $members = [];
        $memberSteps = [];

        $progressIncr = in_array($event->project->bookProject, ["bc","bca"]) ? 25 : 20;
        $progressHalfIncr = in_array($event->project->bookProject, ["bc","bca"]) ? 13 : 10;

        foreach ($event->chapters as $chapter) {
            $tmp["trID"] = $chapter->trID;
            $tmp["memberID"] = $chapter->memberID;
            $tmp["chunks"] = $chapter->chunks ? json_decode($chapter->chunks, true) : [];
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
            $consumeState = StepsStates::NOT_STARTED;
            $blindDraftState = StepsStates::NOT_STARTED;
            $multiDraftState = StepsStates::NOT_STARTED;

            $members[$chapter["memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            $currentChapter = $memberSteps[$chapter["memberID"]]["currentChapter"];
            $otherCheck = $memberSteps[$chapter["memberID"]]["otherCheck"] ? json_decode($memberSteps[$chapter["memberID"]]["otherCheck"], true) : [];
            $peerCheck = $memberSteps[$chapter["memberID"]]["peerCheck"] ? json_decode($memberSteps[$chapter["memberID"]]["peerCheck"], true) : [];

            // Set default values
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["kwc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peerChk"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peerChk"]["checkerID"] = 'na';
            $data["chapters"][$key]["stepChk"] = EventSteps::PRAY;

            // When no chunks created or translation not started
            if (empty($chapter["chunks"]) || !isset($chapter["chunksData"])) {
                if ($currentChapter == $key) {
                    $currentStep = $memberSteps[$chapter["memberID"]]["step"];

                    if ($currentStep == EventSteps::CONSUME) {
                        $consumeState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::BLIND_DRAFT) {
                        $consumeState = StepsStates::FINISHED;
                        $blindDraftState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::MULTI_DRAFT) {
                        $multiDraftState = StepsStates::IN_PROGRESS;
                    }
                }

                $data["chapters"][$key]["step"] = $currentStep;
                $data["chapters"][$key]["consume"]["state"] = $consumeState;
                $data["chapters"][$key]["blindDraft"]["state"] = $blindDraftState;
                $data["chapters"][$key]["multiDraft"]["state"] = $multiDraftState;

                if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += $progressIncr;

                $overallProgress += $data["chapters"][$key]["progress"];

                $data["chapters"][$key]["chunksData"] = [];
                continue;
            }

            $currentStep = $memberSteps[$chapter["memberID"]]["step"];

            $data["chapters"][$key]["progress"] += sizeof($chapter["chunksData"]) * $progressIncr / sizeof($chapter["chunks"]);
            $data["chapters"][$key]["step"] = $currentChapter == $key ? $currentStep : EventSteps::FINISHED;

            $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;

            if ($currentChapter == $key) {
                if ($currentStep == EventSteps::BLIND_DRAFT) {
                    $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::IN_PROGRESS;
                } elseif ($currentStep == EventSteps::MULTI_DRAFT) {
                    $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::IN_PROGRESS;
                } elseif ($currentStep == EventSteps::SELF_CHECK) {
                    $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            }

            // Checking stage
            if (array_key_exists($key, $otherCheck)) {
                $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::FINISHED;
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
                        $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                    }
                }
            }

            // Progress checks
            if (!in_array($event->project->bookProject, ["bc","bca"])
                && $data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED) {
                $data["chapters"][$key]["progress"] += $progressIncr;
            }

            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $progressIncr;
            if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $progressIncr;
            if ($data["chapters"][$key]["peerChk"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += $progressHalfIncr;
            if ($data["chapters"][$key]["peerChk"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += $progressIncr;

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