<?php


namespace App\Domain;


use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;
use Helpers\Constants\StepsStates;

class ScriptureProgress
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
            $tmp["chunks"] = json_decode($chapter->chunks, true);
            $tmp["done"] = $chapter->done;

            $data["chapters"][$chapter->chapter] = $tmp;

            $translator = $chapter->translator;
            if (!array_key_exists($translator->memberID, $memberSteps)) {
                $memberSteps[$translator->memberID]["step"] = $translator->step;
                $memberSteps[$translator->memberID]["verbCheck"] = $translator->verbCheck;
                $memberSteps[$translator->memberID]["peerCheck"] = $translator->peerCheck;
                $memberSteps[$translator->memberID]["kwCheck"] = $translator->kwCheck;
                $memberSteps[$translator->memberID]["crCheck"] = $translator->crCheck;
                $memberSteps[$translator->memberID]["otherCheck"] = $translator->otherCheck;
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
            $multiDraftState = StepsStates::NOT_STARTED;
            $consumeState = StepsStates::NOT_STARTED;
            $verbCheckState = StepsStates::NOT_STARTED;
            $chunkingState = StepsStates::NOT_STARTED;
            $blindDraftState = StepsStates::NOT_STARTED;

            $members[$chapter["memberID"]] = "";
            $data["chapters"][$key]["progress"] = 0;

            $currentChapter = $memberSteps[$chapter["memberID"]]["currentChapter"];
            $verbCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["verbCheck"], true);
            $peerCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["peerCheck"], true);
            $kwCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["kwCheck"], true);
            $crCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["crCheck"], true);
            $otherCheck = (array)json_decode($memberSteps[$chapter["memberID"]]["otherCheck"], true);

            // Set default values
            $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["consume"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["verb"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["verb"]["checkerID"] = "na";
            $data["chapters"][$key]["chunking"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peer"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["peer"]["checkerID"] = "na";
            $data["chapters"][$key]["kwc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["kwc"]["checkerID"] = "na";
            $data["chapters"][$key]["crc"]["state"] = StepsStates::NOT_STARTED;
            $data["chapters"][$key]["crc"]["checkerID"] = "na";
            $data["chapters"][$key]["finalReview"]["state"] = StepsStates::NOT_STARTED;

            // When no chunks created or translation not started
            if (empty($chapter["chunks"]) || !isset($chapter["chunksData"])) {
                if ($currentChapter == $key) {
                    $currentStep = $memberSteps[$chapter["memberID"]]["step"];

                    if ($currentStep == EventSteps::CONSUME) {
                        $consumeState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::VERBALIZE) {
                        $consumeState = StepsStates::FINISHED;
                        if (array_key_exists($key, $verbCheck)) {
                            $verbCheckState = StepsStates::IN_PROGRESS;
                            if ($verbCheck[$key]["done"] > 0) {
                                $verbCheckState = StepsStates::CHECKED;
                                if (is_numeric($verbCheck[$key]["memberID"])) {
                                    $members[$verbCheck[$key]["memberID"]] = "";
                                    $verbChecker = $verbCheck[$key]["memberID"];
                                } else {
                                    $uniqID = uniqid("chk");
                                    $members[$uniqID] = $verbCheck[$key]["memberID"];
                                    $verbCheck[$key] = $uniqID;
                                    $verbChecker = $uniqID;
                                }
                            }
                        } else {
                            $verbCheckState = StepsStates::WAITING;
                        }
                    } elseif ($currentStep == EventSteps::CHUNKING) {
                        $consumeState = StepsStates::FINISHED;
                        $verbCheckState = StepsStates::FINISHED;
                        $chunkingState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::READ_CHUNK || $currentStep == EventSteps::BLIND_DRAFT) {
                        $consumeState = StepsStates::FINISHED;
                        $verbCheckState = StepsStates::FINISHED;
                        $chunkingState = StepsStates::FINISHED;
                        $blindDraftState = StepsStates::IN_PROGRESS;
                    } elseif ($currentStep == EventSteps::MULTI_DRAFT) {
                        $multiDraftState = StepsStates::IN_PROGRESS;
                    }
                }

                $data["chapters"][$key]["step"] = $currentStep;
                $data["chapters"][$key]["consume"]["state"] = $consumeState;
                $data["chapters"][$key]["multiDraft"]["state"] = $multiDraftState;
                $data["chapters"][$key]["verb"]["state"] = $verbCheckState;
                $data["chapters"][$key]["verb"]["checkerID"] = $verbChecker ?? "na";
                $data["chapters"][$key]["chunking"]["state"] = $chunkingState;
                $data["chapters"][$key]["blindDraft"]["state"] = $blindDraftState;

                // Progress checks
                if ($event->inputMode == InputMode::NORMAL) {
                    if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                        $data["chapters"][$key]["progress"] += 11;
                    if ($data["chapters"][$key]["verb"]["state"] == StepsStates::CHECKED)
                        $data["chapters"][$key]["progress"] += 6;
                    if ($data["chapters"][$key]["verb"]["state"] == StepsStates::FINISHED)
                        $data["chapters"][$key]["progress"] += 11;
                    if ($data["chapters"][$key]["chunking"]["state"] == StepsStates::FINISHED)
                        $data["chapters"][$key]["progress"] += 11;
                }

                $overallProgress += $data["chapters"][$key]["progress"];

                $data["chapters"][$key]["chunksData"] = [];
                continue;
            }

            $currentStep = $memberSteps[$chapter["memberID"]]["step"];

            // Total translated chunks are 11% of all chapter progress
            if ($event->inputMode == InputMode::NORMAL)
                $data["chapters"][$key]["progress"] += sizeof($chapter["chunksData"]) * 11 / sizeof($chapter["chunks"]);
            $data["chapters"][$key]["step"] = $currentChapter == $key ? $currentStep : EventSteps::FINISHED;

            // These steps are finished here by default
            $data["chapters"][$key]["multiDraft"]["state"] = StepsStates::FINISHED;
            $data["chapters"][$key]["consume"]["state"] = StepsStates::FINISHED;
            $data["chapters"][$key]["chunking"]["state"] = StepsStates::FINISHED;

            if ($currentChapter == $key) {
                if ($currentStep == EventSteps::READ_CHUNK
                    || $currentStep == EventSteps::BLIND_DRAFT) {
                    $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::IN_PROGRESS;
                } else if ($currentStep == EventSteps::SELF_CHECK) {
                    $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
                    $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::IN_PROGRESS;
                }
            } else {
                $data["chapters"][$key]["selfEdit"]["state"] = StepsStates::FINISHED;
            }

            // Verbalize Check
            if (array_key_exists($key, $verbCheck)) {
                $data["chapters"][$key]["verb"]["state"] = StepsStates::FINISHED;

                if (!is_numeric($verbCheck[$key]["memberID"])) {
                    $uniqID = uniqid("chk");
                    $members[$uniqID] = $verbCheck[$key]["memberID"];
                    $verbCheck[$key]["memberID"] = $uniqID;
                    $data["chapters"][$key]["verb"]["checkerID"] = $verbCheck[$key]["memberID"];
                } else {
                    $data["chapters"][$key]["verb"]["checkerID"] = $verbCheck[$key]["memberID"];
                    $members[$verbCheck[$key]["memberID"]] = "";
                }
            }

            // Checking stage

            // Peer Check
            if (array_key_exists($key, $peerCheck)) {
                $data["chapters"][$key]["blindDraft"]["state"] = StepsStates::FINISHED;
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

            // Verse Markers
            if (array_key_exists($key, $otherCheck)) {
                $data["chapters"][$key]["finalReview"]["state"] = StepsStates::IN_PROGRESS;

                if ($otherCheck[$key]["done"] > 0) {
                    $data["chapters"][$key]["finalReview"]["state"] = StepsStates::FINISHED;
                }
            }

            // Progress checks
            if ($event->inputMode == InputMode::NORMAL) {
                if ($data["chapters"][$key]["consume"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["verb"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["chunking"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["peer"]["state"] == StepsStates::CHECKED)
                    $data["chapters"][$key]["progress"] += 6;
                if ($data["chapters"][$key]["peer"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::CHECKED)
                    $data["chapters"][$key]["progress"] += 6;
                if ($data["chapters"][$key]["kwc"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["crc"]["state"] == StepsStates::CHECKED)
                    $data["chapters"][$key]["progress"] += 6;
                if ($data["chapters"][$key]["crc"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 11;
                if ($data["chapters"][$key]["finalReview"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 12;
            } elseif ($event->inputMode == InputMode::SPEECH_TO_TEXT) {
                if ($data["chapters"][$key]["multiDraft"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 33;
                if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 33;
                if ($data["chapters"][$key]["peer"]["state"] == StepsStates::CHECKED)
                    $data["chapters"][$key]["progress"] += 17;
                if ($data["chapters"][$key]["peer"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 34;
            } else {
                if ($data["chapters"][$key]["multiDraft"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 50;
                if ($data["chapters"][$key]["selfEdit"]["state"] == StepsStates::FINISHED)
                    $data["chapters"][$key]["progress"] += 50;
            }

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