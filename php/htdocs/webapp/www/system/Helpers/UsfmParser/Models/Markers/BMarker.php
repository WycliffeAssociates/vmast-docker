<?php

namespace Helpers\UsfmParser\Models\Markers;

class BMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "b";
    }
}