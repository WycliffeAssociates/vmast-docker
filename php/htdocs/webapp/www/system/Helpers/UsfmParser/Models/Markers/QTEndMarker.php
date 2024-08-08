<?php

namespace Helpers\UsfmParser\Models\Markers;

class QTEndMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "qt*";
    }
}