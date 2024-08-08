<?php

namespace Helpers\UsfmParser\Models\Markers;

class SLSEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sls*";
    }
}