<?php

namespace Helpers\UsfmParser\Models\Markers;

class PCMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pc";
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