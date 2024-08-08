<?php

namespace Helpers\UsfmParser\Models\Markers;

class KEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "k*";
    }
}