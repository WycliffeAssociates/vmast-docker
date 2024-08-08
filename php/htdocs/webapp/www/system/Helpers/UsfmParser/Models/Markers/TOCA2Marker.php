<?php

namespace Helpers\UsfmParser\Models\Markers;

class TOCA2Marker extends Marker
{
    public string $altShortTableOfContentsText;

    public function getIdentifier(): string
    {
        return "toca2";
    }

    public function preProcess(string $input): string
    {
        $this->altShortTableOfContentsText = trim($input);
        return "";
    }
}