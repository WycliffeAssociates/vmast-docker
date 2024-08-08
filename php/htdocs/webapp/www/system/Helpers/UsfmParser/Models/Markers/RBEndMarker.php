<?php

namespace Helpers\UsfmParser\Models\Markers;

class RBEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "rb*";
    }
}