<?php

namespace Helpers\UsfmParser\Models\Markers;

class TOC1Marker extends Marker
{
    public string $longTableOfContentsText;

    public function getIdentifier(): string
    {
        return "toc1";
    }

    public function preProcess(string $input): string
    {
        $this->longTableOfContentsText = trim($input);
        return "";
    }
}