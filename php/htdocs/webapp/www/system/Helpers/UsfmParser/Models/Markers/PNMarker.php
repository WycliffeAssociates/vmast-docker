<?php

namespace Helpers\UsfmParser\Models\Markers;

class PNMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pn";
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