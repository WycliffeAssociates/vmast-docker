<?php

namespace Helpers\UsfmParser\Models\Markers;

class LIVEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "liv*";
    }
}