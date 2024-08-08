<?php

namespace Helpers\UsfmParser\Models\Markers;

class FTMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ft";
    }

    public function preProcess(string $input): string
    {
        return ltrim($input);
    }

    public function getAllowedContents(): array
    {
        return [
            TLMarker::class,
            TLEndMarker::class,
            WMarker::class,
            WEndMarker::class,
            TextBlock::class,
            ITMarker::class,
            ITEndMarker::class,
            SCMarker::class,
            SCEndMarker::class,
            SUPMarker::class,
            SUPEndMarker::class,
            BKMarker::class,
            BKEndMarker::class,
            BDMarker::class,
            BDEndMarker::class,
        ];
    }
}