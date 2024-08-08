<?php

namespace Helpers\UsfmParser\Models\Markers;

class PNGEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "png*";
    }
}