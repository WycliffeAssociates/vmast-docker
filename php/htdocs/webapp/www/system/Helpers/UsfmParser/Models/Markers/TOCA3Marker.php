<?php

namespace Helpers\UsfmParser\Models\Markers;

class TOCA3Marker extends Marker
{
    public string $altBookAbbreviation;

    public function getIdentifier(): string
    {
        return "toca3";
    }

    public function preProcess(string $input): string
    {
        $this->altBookAbbreviation = trim($input);
        return "";
    }
}