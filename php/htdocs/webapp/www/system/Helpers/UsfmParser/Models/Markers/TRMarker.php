<?php

namespace Helpers\UsfmParser\Models\Markers;

class TRMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "tr";
    }

    public function getAllowedContents(): array
    {
        return [
            TCMarker::class,
            THMarker::class,
            TCRMarker::class,
            THRMarker::class
        ];
    }
}