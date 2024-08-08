<?php

namespace Helpers\UsfmParser\Models\Markers;

class SUPEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sup*";
    }
}