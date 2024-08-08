<?php

namespace Helpers\UsfmParser\Models\Markers;

class PMRMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pmr";
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