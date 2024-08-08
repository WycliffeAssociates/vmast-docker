<?php

namespace Helpers\UsfmParser\Models\Markers;

class MSMarker extends Marker
{
    public int $weight = 1;
    public string $heading;

    public function getIdentifier(): string
    {
        return "ms";
    }

    public function preProcess(string $input): string
    {
        $this->heading = ltrim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [MRMarker::class];
    }
}