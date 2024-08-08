<?php

namespace Helpers\UsfmParser\Models\Markers;

class FQEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "fq*";
    }
}