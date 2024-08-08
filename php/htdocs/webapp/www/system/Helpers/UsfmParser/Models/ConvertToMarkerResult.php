<?php

namespace Helpers\UsfmParser\Models;

use Helpers\UsfmParser\Models\Markers\Marker;

/**
 * A holder class to take the place of a tuple
 */
class ConvertToMarkerResult
{
    public Marker $marker;
    public string $remainingText;

    public function __construct(Marker $marker, string $remainingText)
    {
        $this->marker = $marker;
        $this->remainingText = $remainingText;
    }
}