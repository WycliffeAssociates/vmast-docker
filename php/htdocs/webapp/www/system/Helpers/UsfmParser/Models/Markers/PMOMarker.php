<?php

namespace Helpers\UsfmParser\Models\Markers;

class PMOMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pmo";
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