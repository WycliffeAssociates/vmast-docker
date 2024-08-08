<?php

namespace Helpers\UsfmParser\Models\Markers;

class CLMarker extends Marker
{
    public string $label;

    public function getIdentifier(): string
    {
        return "cl";
    }

    public function preProcess(string $input): string
    {
        $this->label = trim($input);
        return "";
    }
}