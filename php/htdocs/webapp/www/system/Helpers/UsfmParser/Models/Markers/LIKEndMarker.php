<?php

namespace Helpers\UsfmParser\Models\Markers;

class LIKEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "lik*";
    }
}