<?php

namespace Helpers\UsfmParser\Models\Markers;

class BKMarker extends Marker
{
    public string $bookTitle;

    public function getIdentifier(): string
    {
        return "bk";
    }

    public function preProcess(string $input): string
    {
        $this->bookTitle = trim($input);
        return "";
    }
}