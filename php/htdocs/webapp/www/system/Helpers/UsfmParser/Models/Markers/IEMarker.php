<?php

namespace Helpers\UsfmParser\Models\Markers;

class IEMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ie";
    }
}