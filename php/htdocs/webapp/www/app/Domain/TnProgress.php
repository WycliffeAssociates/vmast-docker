<?php


namespace App\Domain;


use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;

class TnProgress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];
        for ($i = 0; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        $overallProgress = 0;
        $members = [];
        $memberSteps = [];

        foreach ($event->chapters as $chapter) {
            $tmp["trID"] = $chapter->trID;
            $tmp["memberID"] = $chapter->memberID;
            $tmp["chunks"] = $chapter->chunks ? (array)json_decode($chapter->chunks, true) : [];
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

            $members[$chapter["memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            $currentChapter = $memberSteps[$chapter["memberID"]]["currentChapter"];
            $otherCheck = $memberSteps[$chapter["memberID"]]["otherCheck"] ? (array)json_decode($memberSteps[$chapter["memberID"]]["otherCheck"], true) : [];
            $peerCheck = $memberSteps[$chapter["memberID"]]["peerCheck"] ? (array)json_decode($memberSteps[$chapter["memberID"]]["peerCheck"], true) : [];

            // Set default values
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;

            $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["highlightChk"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEditChk"]["state"] = StepsStates::NOT_STARTED;
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
                    } else if ($currentStep == EventSteps::READ_CHUNK ||
                        $currentStep == EventSteps::BLIND_DRAFT) {
                        $consumeState = StepsStates::FINISHED;
                        $blindDraftState = StepsStates::IN_PROGRESS;
                    }
                }

                $data["chapters"][$key]["step"] = $currentStep;
                $data["chapters"][$key]["consume"]["state"] = $consumeState;
                $data["chapters"][$key]["blindDraft"]["state"] = $blindDraftState;

                // Progress checks
                if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 17;

                $data["chapters"][$key]["chunksData"] = [];
                $overallProgress += $data["chapters"][$key]["progress"];
                continue;
            }

            $currentStep = $memberSteps[$chapter["memberID"]]["step"];

            // Total translated chunks are 17% of all chapter progress
            $data["chapters"][$key]["progress"] += sizeof($chapter["chunksData"]) * 17 / sizeof($chapter["chunks"]);
            $data["chapters"][$key]["step"] = $currentChapter == $key ? $currentStep : EventSteps::FINISHED;

            // These steps are finished here by default
            $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;

            if ($currentChapter == $key) {
                if ($currentStep == EventSteps::READ_CHUNK
                    || $currentStep == EventSteps::BLIND_DRAFT) {
                    $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::IN_PROGRESS;
                } else if ($currentStep == EventSteps::SELF_CHECK) {
                    $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            }

            // TranslationNotes Checking stage
            if (array_key_exists($key, $otherCheck)) {
                $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["step"] = EventSteps::FINISHED;

                if ($otherCheck[$key]["memberID"] > 0) {
                    $members[$otherCheck[$key]["memberID"]] = "";
                    $data["chapters"][$key]["checkerID"] = $otherCheck[$key]["memberID"];

                    if ($otherCheck[$key]["done"] == 6) {
                        $members[$peerCheck[$key]["memberID"]] = "";
                        $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["highlightChk"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["selfEditChk"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["kwc"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["peerChk"]["state"] = StepsStates::FINISHED;
                        $data["chapters"][$key]["peerChk"]["checkerID"] = $peerCheck[$key]["memberID"];
                        $data["chapters"][$key]["stepChk"] = EventSteps::FINISHED;
                    } else {
                        switch ($otherCheck[$key]["done"]) {
                            case 1:
                                $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::IN_PROGRESS;
                                break;
                            case 2:
                                $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["highlightChk"]["state"] = StepsStates::IN_PROGRESS;
                                break;
                            case 3:
                                $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["highlightChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["selfEditChk"]["state"] = StepsStates::IN_PROGRESS;
                                break;
                            case 4:
                                $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["highlightChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["selfEditChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["kwc"]["state"] = StepsStates::IN_PROGRESS;
                                break;
                            case 5:
                                $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["highlightChk"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["selfEditChk"]["state"] = StepsStates::FINISHED;
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
                    $data["chapters"][$key]["consumeChk"]["state"] = StepsStates::WAITING;
                }
            }

            // Count checked chunks
            if (!empty($data["chapters"][$key]["chunksData"])) {
                $arr = [];
                foreach ($data["chapters"][$key]["chunksData"] as $chunkData) {
                    $verses = $chunkData->translatedVerses ? (array)json_decode($chunkData->translatedVerses, true) : [];
                    if (isset($verses["checker"]) && !empty($verses["checker"]->verses))
                        $arr[] = "";
                }

                $data["chapters"][$key]["progress"] += sizeof($arr) * 10 / sizeof($chapter["chunks"]);
            }

            // Progress checks
            if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 17;
            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 17;
            if ($data["chapters"][$key]["consumeChk"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 10;
            if ($data["chapters"][$key]["highlightChk"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 10;
            if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 10;
            if ($data["chapters"][$key]["peerChk"]["state"] == StepsStates::CHECKED)
                $data["chapters"][$key]["progress"] += 5;
            if ($data["chapters"][$key]["peerChk"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 9;

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