<?php

namespace Helpers\UsfmParser\Models\Markers;

class PMCMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pmc";
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