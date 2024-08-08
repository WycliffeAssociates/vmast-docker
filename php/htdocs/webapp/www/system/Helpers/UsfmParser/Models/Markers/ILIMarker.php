<?php

namespace Helpers\UsfmParser\Models\Markers;

class ILIMarker extends Marker
{
    public int $depth = 1;

    public function getIdentifier(): string
    {
        return "ili";
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