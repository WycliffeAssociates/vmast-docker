<?php

namespace Helpers\UsfmParser\Models\Markers;

class CDMarker extends Marker
{
    public string $description;

    public function getIdentifier(): string
    {
        return "cd";
    }

    public function preProcess(string $input): string
    {
        $this->description = $input;
        return "";
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}