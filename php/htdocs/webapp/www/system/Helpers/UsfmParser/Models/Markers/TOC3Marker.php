<?php

namespace Helpers\UsfmParser\Models\Markers;

class TOC3Marker extends Marker
{
    public string $bookAbbreviation;

    public function getIdentifier(): string
    {
        return "toc3";
    }

    public function preProcess(string $input): string
    {
        $this->bookAbbreviation = trim($input);
        return "";
    }
}