<?php

namespace Helpers\UsfmParser\Models\Markers;

class MIMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "mi";
    }

    public function getAllowedContents(): array
    {
        return [
            TextBlock::class,
            VMarker::class,
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