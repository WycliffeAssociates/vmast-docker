<?php

namespace Helpers\UsfmParser\Models\Markers;

class VPEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "vp*";
    }
}