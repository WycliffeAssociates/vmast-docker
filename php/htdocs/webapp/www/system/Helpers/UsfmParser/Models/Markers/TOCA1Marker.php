<?php

namespace Helpers\UsfmParser\Models\Markers;

class TOCA1Marker extends Marker
{
    public string $altLongTableOfContentsText;

    public function getIdentifier(): string
    {
        return "toca1";
    }

    public function preProcess(string $input): string
    {
        $this->altLongTableOfContentsText = trim($input);
        return "";
    }
}