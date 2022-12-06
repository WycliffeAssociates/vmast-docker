<?php

namespace Helpers\Cue;

class CueData {
    private $comment;
    private $title;
    private $fileName;
    private $fileFormat;
    /**
     * @var CueTrack[]
     */
    private $tracks = [];

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment): void {
        $this->comment = $comment;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function setFileName($fileName): void {
        $this->fileName = $fileName;
    }

    public function getFileFormat() {
        return $this->fileFormat;
    }

    public function setFileFormat($fileFormat): void {
        $this->fileFormat = $fileFormat;
    }

    public function getTracks(): array {
        return $this->tracks;
    }

    public function getTrack($index): ?CueTrack {
        return array_key_exists($index, $this->tracks) ? $this->tracks[$index] : null;
    }

    public function setTracks(array $tracks): void {
        $this->tracks = $tracks;
    }

    public function addTrack(CueTrack $track) {
        $this->tracks[] = $track;
    }
}