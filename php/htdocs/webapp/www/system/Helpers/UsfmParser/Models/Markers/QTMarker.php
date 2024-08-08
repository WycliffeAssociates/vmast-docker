<?php

namespace Helpers\UsfmParser\Models\Markers;

class QTMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "qt";
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