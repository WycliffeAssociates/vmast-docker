<?php

namespace Helpers\UsfmParser\Models\Markers;

class RBMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "rb";
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