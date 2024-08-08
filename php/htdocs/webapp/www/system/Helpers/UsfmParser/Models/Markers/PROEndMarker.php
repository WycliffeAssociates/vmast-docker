<?php

namespace Helpers\UsfmParser\Models\Markers;

class PROEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "pro*";
    }
}