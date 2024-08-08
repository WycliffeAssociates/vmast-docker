<?php

namespace Helpers\UsfmParser\Models\Markers;

class ITEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "it*";
    }
}