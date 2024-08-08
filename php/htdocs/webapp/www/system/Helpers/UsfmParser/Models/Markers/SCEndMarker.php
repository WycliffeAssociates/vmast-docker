<?php

namespace Helpers\UsfmParser\Models\Markers;

class SCEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sc*";
    }
}