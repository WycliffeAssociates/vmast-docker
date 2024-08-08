<?php

namespace Helpers\UsfmParser\Models\Markers;

class FMarker extends Marker
{
    public string $footNoteCaller;

    public function getIdentifier(): string
    {
        return "f";
    }

    public function preProcess(string $input): string
    {
        $this->footNoteCaller = trim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            FRMarker::class,
            FREndMarker::class,
            FKMarker::class,
            FTMarker::class,
            FVMarker::class,
            FVEndMarker::class,
            FPMarker::class,
            FQAMarker::class,
            FQAEndMarker::class,
            FQMarker::class,
            FQEndMarker::class,
            TLMarker::class,
            TLEndMarker::class,
            WMarker::class,
            WEndMarker::class,
            TextBlock::class,
            ITMarker::class,
            ITEndMarker::class,
            SCMarker::class,
            SCEndMarker::class,
            SUPMarker::class,
            SUPEndMarker::class,
            BKMarker::class,
            BKEndMarker::class,
            BDMarker::class,
            BDEndMarker::class
        ];
    }
}