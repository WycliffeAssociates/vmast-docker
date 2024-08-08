<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Hebrew note
 */
class QDMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "qd";
    }

    public function preProcess(string $input): string
    {
        return ltrim($input);
    }

    public function getAllowedContents(): array
    {
        return [
            TextBlock::class,
            FMarker::class,
            FEndMarker::class,
            TLMarker::class,
            TLEndMarker::class,
            WMarker::class,
            WEndMarker::class
        ];
    }
}