<?php

namespace Helpers\UsfmParser\Models\Markers;

class IQMarker extends Marker
{
    public int $depth = 1;
    public string $text;

    public function getIdentifier(): string
    {
        return "iq";
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