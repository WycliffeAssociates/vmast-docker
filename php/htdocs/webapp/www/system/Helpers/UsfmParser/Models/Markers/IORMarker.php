<?php

namespace Helpers\UsfmParser\Models\Markers;

class IORMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ior";
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