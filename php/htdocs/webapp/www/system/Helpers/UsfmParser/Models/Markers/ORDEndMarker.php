<?php

namespace Helpers\UsfmParser\Models\Markers;

class ORDEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "ord*";
    }
}