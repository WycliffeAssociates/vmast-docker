<?php

namespace Helpers\UsfmParser\Models\Markers;

class CAEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ca*";
    }
}