<?php

namespace Helpers\UsfmParser\Models\Markers;

class PMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "p";
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
            QMarker::class,
            XMarker::class
        ];
    }
}