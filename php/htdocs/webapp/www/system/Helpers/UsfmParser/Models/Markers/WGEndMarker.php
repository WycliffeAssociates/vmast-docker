<?php

namespace Helpers\UsfmParser\Models\Markers;

class WGEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wg*";
    }
}