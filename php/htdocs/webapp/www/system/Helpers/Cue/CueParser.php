<?php

namespace Helpers\Cue;

class CueParser {

    private const TIME_REGEX = "(\d+):(\d{2}):(\d{2})";

    private $cueData;
    private $trackIndex = -1;

    function __construct() {
        $this->cueData = new CueData();
    }

    function parse($cueData): CueData {
        $lines = preg_split("/\n/", $cueData);

        foreach ($lines as $line) {
            $this->processLine($line);
        }

        return $this->cueData;
    }

    private function processLine($line) {
        $this->processComment($line);
        $this->processMainTitle($line);
        $this->processFile($line);
        $this->processTrack($line);
        $this->processTrackTitle($line);
        $this->processIndex($line);
    }

    private function processComment($line) {
        if (preg_match("/^REM COMMENT\s(.*)/", trim($line), $matches)) {
            if (isset($matches[1])) {
                $this->cueData->setComment($matches[1]);
            }
        }
    }

    private function processMainTitle($line) {
        if (empty($this->cueData->getTracks()) && preg_match("/^TITLE\s(.*)/", trim($line), $matches)) {
            if (isset($matches[1])) {
                $this->cueData->setTitle(str_replace("\"", "", $matches[1]));
            }
        }
    }

    private function processFile($line) {
        if (preg_match("/^FILE\s(.*?)\s(WAVE|MP3)/", trim($line), $matches)) {
            if (isset($matches[1])) {
                $fileName = str_replace("\"", "", $matches[1]);
                $this->cueData->setFileName($fileName);
            }
            if (isset($matches[2])) {
                $this->cueData->setFileFormat($matches[2]);
            }
        }
    }

    private function processTrack($line) {
        if (preg_match("/^TRACK\s(.*?)\s(AUDIO)/", trim($line), $matches)) {
            $track = new CueTrack();
            if (isset($matches[1])) {
                $track->setTitle($matches[1]);
            }
            if (isset($matches[2])) {
                $track->setType($matches[2]);
            }
            $this->cueData->addTrack($track);
            $this->trackIndex++;
        }
    }

    private function processTrackTitle($line) {
        if (!empty($this->cueData->getTracks()) && preg_match("/^TITLE\s(.*)/", trim($line), $matches)) {
            if (isset($matches[1])) {
                $track = $this->cueData->getTrack($this->trackIndex);
                if ($track) $track->setNumber($matches[1]);
            }
        }
    }

    private function processIndex($line) {
        if (!empty($this->cueData->getTracks()) && preg_match("/^INDEX\s\d+\s".CueParser::TIME_REGEX."/", trim($line), $matches)) {
            if (sizeof($matches) == 4) {
                $minutes = (int)$matches[1];
                $seconds = (int)$matches[2];
                $frames = (int)$matches[3];

                $track = $this->cueData->getTrack($this->trackIndex);

                $position = 0.0;
                if ($track && $this->trackIndex > 0) {
                    $position = $track->getPosition();
                }

                $position += $this->framesToSeconds($minutes, $seconds, $frames);
                if ($track) $track->setPosition($position);
            }
        }
    }

    private function framesToSeconds($minutes, $seconds, $frames): float {
        $total = 0.0;
        $total += $minutes * 60;
        $total += $seconds;
        $total += $frames / 75;
        return $total;
    }
}