<?php

namespace Helpers\UsfmParser\Models\Markers;

class WJMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wj";
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