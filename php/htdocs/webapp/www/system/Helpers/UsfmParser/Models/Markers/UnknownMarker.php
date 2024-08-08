<?php

namespace Helpers\UsfmParser\Models\Markers;

class UnknownMarker extends Marker
{
    public string $parsedIdentifier;
    public string $parsedValue;

    public function getIdentifier(): string
    {
        return "";
    }

    public function preProcess(string $input): string
    {
        $this->parsedValue = $input;
        return "";
    }
}