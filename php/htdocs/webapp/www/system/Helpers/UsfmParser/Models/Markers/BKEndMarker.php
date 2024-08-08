<?php

namespace Helpers\UsfmParser\Models\Markers;

class BKEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "bk*";
    }
}