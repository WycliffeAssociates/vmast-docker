<?php

namespace Helpers\UsfmParser\Models\Markers;

class EMEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "em*";
    }
}