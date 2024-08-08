<?php

namespace Helpers\UsfmParser\Models\Markers;

class IMMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "im";
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