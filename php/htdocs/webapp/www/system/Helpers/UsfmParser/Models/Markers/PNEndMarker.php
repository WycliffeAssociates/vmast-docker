<?php

namespace Helpers\UsfmParser\Models\Markers;

class PNEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pn*";
    }
}