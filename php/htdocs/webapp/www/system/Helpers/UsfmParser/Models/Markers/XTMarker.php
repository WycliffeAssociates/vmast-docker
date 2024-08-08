<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Target reference(s)
 */
class XTMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "xt";
    }

    public function preProcess(string $input): string
    {
        return ltrim($input);
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}