<?php

namespace Helpers\UsfmParser\Models\Markers;

class FIGEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "fig*";
    }
}