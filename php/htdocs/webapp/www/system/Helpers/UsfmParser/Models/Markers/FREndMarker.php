<?php

namespace Helpers\UsfmParser\Models\Markers;

class FREndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "fr*";
    }
}