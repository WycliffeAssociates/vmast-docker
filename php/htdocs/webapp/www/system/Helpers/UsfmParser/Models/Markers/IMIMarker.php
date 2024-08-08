<?php

namespace Helpers\UsfmParser\Models\Markers;

class IMIMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "imi";
    }

    public function preProcess(string $input): string
    {
        return trim($input);
    }

    public function getAllowedContents(): array
    {
        return [
            TextBlock::class,
            BKMarker::class,
            BKEndMarker::class,
            BDMarker::class,
            BDEndMarker::class,
            ITMarker::class,
            ITEndMarker::class
        ];
    }
}