<?php

namespace Helpers\UsfmParser\Models\Markers;

class LIMarker extends Marker
{
    public int $depth = 1;

    public function getIdentifier(): string
    {
        return "li";
    }

    public function preProcess(string $input): string
    {
        return trim($input);
    }

    public function getAllowedContents(): array
    {
        return [
            VMarker::class,
            TextBlock::class
        ];
    }
}