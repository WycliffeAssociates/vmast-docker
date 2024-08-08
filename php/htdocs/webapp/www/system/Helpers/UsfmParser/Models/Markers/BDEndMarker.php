<?php

namespace Helpers\UsfmParser\Models\Markers;

class BDEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "bd*";
    }
}