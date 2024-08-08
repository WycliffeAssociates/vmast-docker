<?php

namespace Helpers\UsfmParser\Models\Markers;

class PIMarker extends Marker
{
    public int $depth = 1;

    public function getIdentifier(): string
    {
        return "pi";
    }

    public function preProcess(string $input): string
    {
        return ltrim($input);
    }

    public function getAllowedContents(): array
    {
        return [
            VMarker::class,
            BMarker::class,
            SPMarker::class,
            TextBlock::class,
            FMarker::class,
            FEndMarker::class,
            LIMarker::class,
            QMarker::class
        ];
    }
}