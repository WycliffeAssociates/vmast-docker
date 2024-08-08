<?php

namespace Helpers\UsfmParser\Models\Markers;

class THRMarker extends Marker
{
    public int $columnPosition = 1;

    public function getIdentifier(): string
    {
        return "thr";
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