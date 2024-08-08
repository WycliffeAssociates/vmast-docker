<?php

namespace Helpers\UsfmParser\Models\Markers;

class IOMarker extends Marker
{
    public int $depth = 1;

    public function getIdentifier(): string
    {
        return "io";
    }

    public function getAllowedContents(): array
    {
        return [
            TextBlock::class,
            IORMarker::class,
            IOREndMarker::class
        ];
    }
}