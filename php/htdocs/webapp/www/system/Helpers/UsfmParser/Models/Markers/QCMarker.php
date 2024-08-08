<?php

namespace Helpers\UsfmParser\Models\Markers;

class QCMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "qc";
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