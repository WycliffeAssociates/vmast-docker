<?php

namespace Helpers\UsfmParser\Models\Markers;

class MMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "m";
    }

    public function getAllowedContents(): array
    {
        return [
            VMarker::class,
            TextBlock::class,
            BKMarker::class,
            BKEndMarker::class,
            BDMarker::class,
            BDEndMarker::class,
            ITMarker::class,
            ITEndMarker::class,
            SCMarker::class,
            SCEndMarker::class,
            BDITMarker::class,
            BDITEndMarker::class,
            NDMarker::class,
            NDEndMarker::class,
            NOMarker::class,
            NOEndMarker::class,
            SUPMarker::class,
            SUPEndMarker::class
        ];
    }
}