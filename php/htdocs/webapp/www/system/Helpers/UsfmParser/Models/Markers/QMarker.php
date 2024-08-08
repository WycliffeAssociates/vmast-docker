<?php

namespace Helpers\UsfmParser\Models\Markers;

class QMarker extends Marker
{
    public int $depth = 1;
    public string $text;
    public bool $isPoetryBlock = false;

    public function getIdentifier(): string
    {
        return "q";
    }

    public function preProcess(string $input): string
    {
        return ltrim($input);
    }

    public function getAllowedContents(): array
    {
        return [
            BMarker::class,
            QSMarker::class,
            QSEndMarker::class,
            TextBlock::class,
            FMarker::class,
            FEndMarker::class,
            TLMarker::class,
            TLEndMarker::class,
            WMarker::class,
            WEndMarker::class,
            VMarker::class
        ];
    }

    public function tryInsert(Marker $input): bool
    {
        if ($input::class == VMarker::class && array_any($this->contents, fn($m) => $m::class == VMarker::class)) {
            return false;
        }

        return parent::tryInsert($input);
    }
}