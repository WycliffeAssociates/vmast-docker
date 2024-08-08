<?php

namespace Helpers\UsfmParser\Models\Markers;

class BDITEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "bdit*";
    }
}