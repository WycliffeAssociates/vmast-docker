<?php

namespace Helpers\UsfmParser\Models\Markers;

class FEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "f*";
    }
}