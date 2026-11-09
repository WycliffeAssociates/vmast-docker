<?php


namespace App\Domain;


use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;

class SunProgress
{
    public static function calculateEventProgress($event, $progressOnly = false) {
        $data = [];
        $data["overall_progress"] = 0;
        $data["chapters"] = [];
        for ($i = 1; $i <= $event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        $overallProgress = 0;
        $memberSteps = [];
        $members = [];

        foreach ($event->chapters as $chapter) {
            $tmp["trID"] = $chapter->trID;
            $tmp["memberID"] = $chapter->memberID;
            $tmp["chunks"] = $chapter->chunks ? (array)json_decode($chapter->chunks, true) : [];
            $tmp["done"] = $chapter->done;

            $data["chapters"][$chapter->chapter] = $tmp;

            $translator = $chapter->translator;
            if (!array_key_exists($translator->memberID, $memberSteps)) {
                $memberSteps[$translator->memberID]["step"] = $translator->step;
                $memberSteps[$translator->memberID]["kwCheck"] = $translator->kwCheck;
                $memberSteps[$translator->memberID]["crCheck"] = $translator->crCheck;
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
            $chunkingState = StepsStates::NOT_STARTED;
            $rearrangeState = StepsStates::NOT_STARTED;
            $symbolDraftState = StepsStates::NOT_STARTED;

            $members[$chapter["memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            $currentChapter = $memberSteps[$chapter["memberID"]]["currentChapter"];
            $kwCheck = $memberSteps[$chapter["memberID"]]["kwCheck"] ? (array)json_decode($memberSteps[$chapter["memberID"]]["kwCheck"], true) : [];
            $crCheck = $memberSteps[$chapter["memberID"]]["crCheck"] ? (array)json_decode($memberSteps[$chapter["memberID"]]["crCheck"], true) : [];

            // Set default values
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["chunking"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["rearrange"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["symbolDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;

            $data["chapters"][$key]["theoChk"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["theoChk"]["checkerID"] = 'na';
            $data["chapters"][$key]["crc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["crc"]["checkerID"] = 'na';
            $data["chapters"][$key]["finalReview"]["state"] = StepsStates::NOT_STARTED;

            // When no chunks created or translation not started
            if (empty($chapter["chunks"]) || !isset($chapter["chunksData"])) {
                if ($currentChapter == $key) {
                    $currentStep = $memberSteps[$chapter["memberID"]]["step"];

                    if ($currentStep == EventSteps::CONSUME) {
                        $consumeState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::CHUNKING) {
                        $consumeState = StepsStates::FINISHED;
                        $chunkingState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::REARRANGE) {
                        $consumeState = StepsStates::FINISHED;
                        $chunkingState = StepsStates::FINISHED;
                        $rearrangeState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::SYMBOL_DRAFT) {
                        $consumeState = StepsStates::FINISHED;
                        $chunkingState = StepsStates::FINISHED;
                        $rearrangeState = StepsStates::FINISHED;
                        $symbolDraftState = StepsStates::IN_PROGRESS;
                    }
                }

                $data["chapters"][$key]["step"] = $currentStep;
                $data["chapters"][$key]["consume"]["state"] = $consumeState;
                $data["chapters"][$key]["chunking"]["state"] = $chunkingState;
                $data["chapters"][$key]["rearrange"]["state"] = $rearrangeState;
                $data["chapters"][$key]["symbolDraft"]["state"] = $symbolDraftState;

                // Progress checks
                if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 12.5;
                if ($data["chapters"][$key]["chunking"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 12.5;
                if ($data["chapters"][$key]["rearrange"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 12.5;
                if ($data["chapters"][$key]["symbolDraft"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 12.5;

                $overallProgress += $data["chapters"][$key]["progress"];

                $data["chapters"][$key]["chunksData"] = [];
                continue;
            }

            $currentStep = $memberSteps[$chapter["memberID"]]["step"];

            $kw = !empty($kwCheck)
                && array_key_exists($key, $kwCheck);
            $cr = !empty($crCheck)
                && array_key_exists($key, $crCheck)
                && $crCheck[$key]["memberID"] > 0;

            if ($kw) {
                // Theo check
                $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["chunking"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["rearrange"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["symbolDraft"]["state"] = StepsStates::FINISHED;
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;

                if ($kwCheck[$key]["memberID"] > 0) {
                    $members[$kwCheck[$key]["memberID"]] = "";
                    $data["chapters"][$key]["theoChk"]["checkerID"] = $kwCheck[$key]["memberID"];

                    if ($kwCheck[$key]["done"] == 1) {
                        // Verse-by-verse check
                        $data["chapters"][$key]["theoChk"]["state"] = StepsStates::FINISHED;

                        if ($cr) {
                            $members[$crCheck[$key]["memberID"]] = "";
                            $data["chapters"][$key]["crc"]["checkerID"] = $crCheck[$key]["memberID"];

                            if ($crCheck[$key]["done"] == 2) {
                                $data["chapters"][$key]["crc"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["finalReview"]["state"] = StepsStates::FINISHED;
                            } elseif ($crCheck[$key]["done"] == 1) {
                                $data["chapters"][$key]["crc"]["state"] = StepsStates::FINISHED;
                                $data["chapters"][$key]["finalReview"]["state"] = StepsStates::IN_PROGRESS;
                            } else {
                                $data["chapters"][$key]["crc"]["state"] = StepsStates::IN_PROGRESS;
                            }
                        } else {
                            $data["chapters"][$key]["crc"]["state"] = StepsStates::WAITING;
                        }
                    } else {
                        $data["chapters"][$key]["theoChk"]["state"] = StepsStates::IN_PROGRESS;
                    }
                } else {
                    $data["chapters"][$key]["theoChk"]["state"] = StepsStates::WAITING;
                }
            } else {
                if ($currentStep == EventSteps::CONSUME) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::IN_PROGRESS;
                } elseif ($currentStep == EventSteps::CHUNKING) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["chunking"]["state"] = StepsStates::IN_PROGRESS;
                } elseif ($currentStep == EventSteps::REARRANGE) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["chunking"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["rearrange"]["state"] = StepsStates::IN_PROGRESS;
                } elseif ($currentStep == EventSteps::SYMBOL_DRAFT) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["chunking"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["rearrange"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["symbolDraft"]["state"] = StepsStates::IN_PROGRESS;
                } elseif ($currentStep == EventSteps::SELF_CHECK) {
                    $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["chunking"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["rearrange"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["symbolDraft"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            }


            // Progress checks
            if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["chunking"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["rearrange"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["symbolDraft"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["theoChk"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["crc"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;
            if ($data["chapters"][$key]["finalReview"]["state"] == StepsStates::FINISHED)
                $data["chapters"][$key]["progress"] += 12.5;

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