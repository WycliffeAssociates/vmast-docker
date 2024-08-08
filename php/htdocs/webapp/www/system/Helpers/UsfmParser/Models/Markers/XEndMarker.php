<?php

namespace Helpers\UsfmParser\Models\Markers;

class XEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "x*";
    }
}