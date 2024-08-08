<?php

namespace Helpers\UsfmParser\Models\Markers;

class IMTMarker extends Marker
{
    public int $weight = 1;
    public string $introTitle;

    public function getIdentifier(): string
    {
        return "imt";
    }

    public function preProcess(string $input): string
    {
        $this->introTitle = trim($input);
        return "";
    }
}