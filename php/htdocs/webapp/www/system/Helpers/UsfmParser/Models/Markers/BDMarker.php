<?php

namespace Helpers\UsfmParser\Models\Markers;

class BDMarker extends Marker
{
    public string $text;

    public function getIdentifier(): string
    {
        return "bd";
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