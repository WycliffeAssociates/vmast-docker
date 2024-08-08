<?php

namespace Helpers\UsfmParser\Models\Markers;

class FVEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "fv*";
    }
}