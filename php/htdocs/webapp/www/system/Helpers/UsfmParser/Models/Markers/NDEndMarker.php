<?php

namespace Helpers\UsfmParser\Models\Markers;

class NDEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "nd*";
    }
}