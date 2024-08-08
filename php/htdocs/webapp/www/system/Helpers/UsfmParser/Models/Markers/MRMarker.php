<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Major section reference marker
 */
class MRMarker extends Marker
{
    public int $weight = 1;
    public string $sectionReference;

    public function getIdentifier(): string
    {
        return "mr";
    }

    public function preProcess(string $input): string
    {
        $this->sectionReference = ltrim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            FMarker::class,
            FEndMarker::class
        ];
    }
}