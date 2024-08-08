<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Cross-reference origin reference
 */
class XOMarker extends Marker
{
    public string $originRef;

    public function getIdentifier(): string
    {
        return "xo";
    }

    public function preProcess(string $input): string
    {
        $this->originRef = trim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}