<?php

namespace Helpers\UsfmParser\Models\Markers;

class RQMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "rq";
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