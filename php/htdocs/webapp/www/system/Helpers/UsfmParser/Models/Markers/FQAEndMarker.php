<?php

namespace Helpers\UsfmParser\Models\Markers;

class FQAEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "fqa*";
    }
}