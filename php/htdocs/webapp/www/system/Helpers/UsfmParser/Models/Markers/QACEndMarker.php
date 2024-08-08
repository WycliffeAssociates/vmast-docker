<?php

namespace Helpers\UsfmParser\Models\Markers;

class QACEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "qac*";
    }
}