<?php

namespace Helpers\UsfmParser\Models\Markers;

class TLEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "tl*";
    }
}