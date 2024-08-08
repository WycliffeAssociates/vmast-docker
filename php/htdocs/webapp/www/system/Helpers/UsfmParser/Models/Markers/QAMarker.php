<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Acrostic heading for poetry
 */
class QAMarker extends Marker
{
    public string $heading;

    public function getIdentifier(): string
    {
        return "qa";
    }

    public function preProcess(string $input): string
    {
        $this->heading = trim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            QACMarker::class,
            QACEndMarker::class
        ];
    }
}