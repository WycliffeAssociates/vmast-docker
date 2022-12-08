<?php

namespace Helpers\Manifest;

use Helpers\Cue\CueParser;

class MediaData {
    private $audioUrl;
    private $cueUrl;
    private $cueData;

    function __construct($audioUrl = null, $cueUrl = null) {
        $this->audioUrl = $audioUrl;
        $this->cueUrl = $cueUrl;
    }

    function getAudioUrl() {
        return $this->audioUrl;
    }

    function setAudioUrl($url): void {
        $this->audioUrl = $url;
    }

    function getCueUrl() {
        return $this->cueUrl;
    }

    public function setCueUrl($cueUrl): void {
        $this->cueUrl = $cueUrl;
    }

    public function getCueData() {
        return $this->cueData;
    }

    public function setCueData($cueData) {
        $this->parseCueData($cueData);
    }

    private function parseCueData($cueData) {
        $parser = new CueParser();
        $this->cueData = $parser->parse($cueData);
    }
}