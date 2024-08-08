<?php

namespace Helpers\UsfmParser\Models\Markers;

class PROMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pro";
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