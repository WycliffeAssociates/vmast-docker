<?php

namespace Helpers\UsfmParser\Models\Markers;

class ADDEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "add*";
    }
}