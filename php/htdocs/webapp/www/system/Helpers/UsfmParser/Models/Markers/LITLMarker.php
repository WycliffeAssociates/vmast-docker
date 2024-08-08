<?php

namespace Helpers\UsfmParser\Models\Markers;

class LITLMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "litl";
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