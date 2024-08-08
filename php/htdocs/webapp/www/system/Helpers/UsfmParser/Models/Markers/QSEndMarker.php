<?php

namespace Helpers\UsfmParser\Models\Markers;

class QSEndMarker extends QSMarker
{
    public function getIdentifier(): string
    {
        return "qs*";
    }
}