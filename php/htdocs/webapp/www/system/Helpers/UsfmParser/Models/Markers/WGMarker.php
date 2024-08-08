<?php

namespace Helpers\UsfmParser\Models\Markers;

class WGMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wg";
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