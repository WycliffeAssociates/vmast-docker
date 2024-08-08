<?php

namespace Helpers\UsfmParser\Models\Markers;

class WJEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wj*";
    }
}