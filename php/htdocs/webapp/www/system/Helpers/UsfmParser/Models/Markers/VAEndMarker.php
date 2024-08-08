<?php

namespace Helpers\UsfmParser\Models\Markers;

class VAEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "va*";
    }
}