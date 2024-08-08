<?php

namespace Helpers\UsfmParser\Models\Markers;

class NDMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "nd";
    }

    public function preProcess(string $input): string
    {
        return trim($input);
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}