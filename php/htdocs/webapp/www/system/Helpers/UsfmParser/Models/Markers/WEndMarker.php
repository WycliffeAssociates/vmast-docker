<?php

namespace Helpers\UsfmParser\Models\Markers;

class WEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "w*";
    }
}