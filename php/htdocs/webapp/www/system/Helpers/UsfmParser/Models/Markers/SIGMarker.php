<?php

namespace Helpers\UsfmParser\Models\Markers;

class SIGMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sig";
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