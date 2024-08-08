<?php

namespace Helpers\UsfmParser\Models\Markers;

class SLSMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sls";
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