<?php

namespace Helpers\UsfmParser\Models\Markers;

class THMarker extends Marker
{
    public int $columnPosition = 1;

    public function getIdentifier(): string
    {
        return "th";
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