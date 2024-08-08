<?php

namespace Helpers\UsfmParser\Models\Markers;

class NOMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "no";
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