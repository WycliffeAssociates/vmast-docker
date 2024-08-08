<?php

namespace Helpers\UsfmParser\Models\Markers;

class WAMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wa";
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