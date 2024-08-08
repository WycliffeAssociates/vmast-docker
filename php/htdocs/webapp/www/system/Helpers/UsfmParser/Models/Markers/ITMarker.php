<?php

namespace Helpers\UsfmParser\Models\Markers;

class ITMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "it";
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