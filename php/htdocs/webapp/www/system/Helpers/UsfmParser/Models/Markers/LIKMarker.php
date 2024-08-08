<?php

namespace Helpers\UsfmParser\Models\Markers;

class LIKMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "lik";
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