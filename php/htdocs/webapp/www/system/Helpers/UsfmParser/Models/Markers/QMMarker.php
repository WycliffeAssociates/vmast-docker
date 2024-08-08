<?php

namespace Helpers\UsfmParser\Models\Markers;

class QMMarker extends Marker
{
    public int $depth = 1;

    public function getIdentifier(): string
    {
        return "qm";
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