<?php

namespace Helpers\UsfmParser\Models\Markers;

class ORDMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ord";
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