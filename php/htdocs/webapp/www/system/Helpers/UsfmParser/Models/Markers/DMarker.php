<?php

namespace Helpers\UsfmParser\Models\Markers;

class DMarker extends Marker
{
    public string $description;

    public function getIdentifier(): string
    {
        return "d";
    }

    public function preProcess(string $input): string
    {
        $this->description = trim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            FMarker::class,
            FEndMarker::class,
            ITMarker::class,
            ITEndMarker::class,
            TextBlock::class
        ];
    }
}