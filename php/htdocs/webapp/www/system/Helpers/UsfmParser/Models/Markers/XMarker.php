<?php

namespace Helpers\UsfmParser\Models\Markers;

class XMarker extends Marker
{
    public string $crossRefCaller;

    public function getIdentifier(): string
    {
        return "x";
    }

    public function preProcess(string $input): string
    {
        $this->crossRefCaller = trim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            XOMarker::class,
            XTMarker::class,
            XQMarker::class,
            TextBlock::class
        ];
    }
}