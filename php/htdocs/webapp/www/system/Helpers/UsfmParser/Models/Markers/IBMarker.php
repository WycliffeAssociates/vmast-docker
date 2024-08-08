<?php

namespace Helpers\UsfmParser\Models\Markers;

class IBMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ib";
    }
}