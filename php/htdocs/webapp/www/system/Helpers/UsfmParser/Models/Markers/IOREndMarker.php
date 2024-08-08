<?php

namespace Helpers\UsfmParser\Models\Markers;

class IOREndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ior*";
    }
}