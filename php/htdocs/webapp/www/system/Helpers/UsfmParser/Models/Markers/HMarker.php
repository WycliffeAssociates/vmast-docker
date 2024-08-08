<?php

namespace Helpers\UsfmParser\Models\Markers;

class HMarker extends Marker
{
    public string $headerText;

    public function getIdentifier(): string
    {
        return "h";
    }

    public function preProcess(string $input): string
    {
        $this->headerText = trim($input);
        return "";
    }
}