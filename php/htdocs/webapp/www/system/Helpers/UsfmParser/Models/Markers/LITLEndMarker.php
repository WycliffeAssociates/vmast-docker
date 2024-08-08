<?php

namespace Helpers\UsfmParser\Models\Markers;

class LITLEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "litl*";
    }
}