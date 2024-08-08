<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * A speaker Marker (Used mostly in Job and Songs of Solomon)
 */
class SPMarker extends Marker
{
    public string $speaker;

    public function getIdentifier(): string
    {
        return "sp";
    }

    public function preProcess(string $input): string
    {
        $this->speaker = trim($input);
        return "";
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}