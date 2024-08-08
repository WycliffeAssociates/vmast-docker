<?php

namespace Helpers\UsfmParser\Models\Markers;

class FRMarker extends Marker
{
    public string $verseReference;

    public function getIdentifier(): string
    {
        return "fr";
    }

    public function preProcess(string $input): string
    {
        $this->verseReference = trim($input);
        return "";
    }
}