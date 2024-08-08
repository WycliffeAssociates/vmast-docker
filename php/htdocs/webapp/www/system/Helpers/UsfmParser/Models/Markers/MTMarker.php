<?php

namespace Helpers\UsfmParser\Models\Markers;

class MTMarker extends Marker
{
    public int $weight = 1;
    public string $title;

    public function getIdentifier(): string
    {
        return "mt";
    }

    public function preProcess(string $input): string
    {
        $this->title = trim($input);
        return "";
    }
}