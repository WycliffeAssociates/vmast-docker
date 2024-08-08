<?php

namespace Helpers\UsfmParser\Models\Markers;

class NOEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "no*";
    }
}