<?php

namespace Helpers\UsfmParser\Models\Markers;

class ISMarker extends Marker
{
    public int $weight = 1;
    public string $heading;

    public function getIdentifier(): string
    {
        return "is";
    }

    public function preProcess(string $input): string
    {
        $this->heading = trim($input);
        return "";
    }
}