<?php

namespace Helpers\UsfmParser\Models\Markers;

class RQEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "rq*";
    }
}