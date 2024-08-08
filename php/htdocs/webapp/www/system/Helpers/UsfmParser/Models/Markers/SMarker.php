<?php

namespace Helpers\UsfmParser\Models\Markers;

class SMarker extends Marker
{
    public int $weight = 1;
    public string $text;

    public function getIdentifier(): string
    {
        return "s";
    }

    public function preProcess(string $input): string
    {
        $this->text = ltrim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            RMarker::class,
            FMarker::class,
            FEndMarker::class,
            SCMarker::class,
            SCEndMarker::class,
            EMMarker::class,
            EMEndMarker::class,
            BDMarker::class,
            BDEndMarker::class,
            ITMarker::class,
            ITEndMarker::class,
            BDITMarker::class,
            BDITEndMarker::class,
            NOMarker::class,
            NOEndMarker::class,
            SUPMarker::class,
            SUPEndMarker::class,
            TextBlock::class
        ];
    }
}