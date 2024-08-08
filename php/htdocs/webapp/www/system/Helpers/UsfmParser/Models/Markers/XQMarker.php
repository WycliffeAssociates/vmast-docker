<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * A quotation from the scripture text
 */
class XQMarker extends Marker
{
    public function getIdentifier(): string
    {
        return "xq";
    }

    public function preProcess(string $input): string
    {
        return trim($input);
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}