<?php

namespace Helpers\UsfmParser\Models\Markers;

class QACMarker extends Marker
{
    public string $acrosticLetter;

    public function getIdentifier(): string
    {
        return "qac";
    }

    public function preProcess(string $input): string
    {
        $this->acrosticLetter = trim($input);
        return "";
    }
}