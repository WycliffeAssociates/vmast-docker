<?php

namespace Helpers\UsfmParser\Models\Markers;

class WHEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wh*";
    }
}