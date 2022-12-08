<?php

namespace Helpers\Cue;

class CueTrack {
    private $number;
    private $type;
    private $title;
    private $position;

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number): void {
        $this->number = $number;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type): void {
        $this->type = $type;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position): void {
        $this->position = $position;
    }
}