<?php

namespace Helpers\UsfmParser\Models\Markers;

class IDEMarker extends Marker
{
    public string $encoding;

    public function getIdentifier(): string
    {
        return "ide";
    }

    public function preProcess(string $input): string
    {
        $this->encoding = trim($input);
        return "";
    }
}