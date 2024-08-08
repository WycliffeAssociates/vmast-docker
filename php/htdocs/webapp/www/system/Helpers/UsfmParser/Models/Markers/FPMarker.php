<?php

namespace Helpers\UsfmParser\Models\Markers;

class FPMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "fp";
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}