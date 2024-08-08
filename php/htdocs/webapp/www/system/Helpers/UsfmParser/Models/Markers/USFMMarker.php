<?php

namespace Helpers\UsfmParser\Models\Markers;

class USFMMarker extends Marker
{
    /**
     * Marker for USFM version
     * @var string
     */
    public string $version;

    public function getIdentifier(): string
    {
        return "usfm";
    }

    public function preProcess(string $input): string
    {
        $this->version = trim($input);
        return "";
    }
}