<?php

namespace Helpers\UsfmParser\Models\Markers;

class WAEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "wa*";
    }
}