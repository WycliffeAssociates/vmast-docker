<?php

namespace Helpers\UsfmParser\Models\Markers;

class SIGEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "sig*";
    }
}