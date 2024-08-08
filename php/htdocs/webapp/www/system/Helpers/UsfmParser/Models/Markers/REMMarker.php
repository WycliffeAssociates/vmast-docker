<?php

namespace Helpers\UsfmParser\Models\Markers;

class REMMarker extends Marker
{
    public string $comment;

    public function getIdentifier(): string
    {
        return "rem";
    }

    public function preProcess(string $input): string
    {
        $this->comment = trim($input);
        return "";
    }
}