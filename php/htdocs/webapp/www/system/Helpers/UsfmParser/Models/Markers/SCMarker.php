<?php

namespace Helpers\UsfmParser\Models\Markers;

class SCMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sc";
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