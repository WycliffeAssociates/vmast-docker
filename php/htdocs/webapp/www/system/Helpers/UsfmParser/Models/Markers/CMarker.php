<?php

namespace Helpers\UsfmParser\Models\Markers;

class CMarker extends Marker
{
    private static string $regex = "/ *(\d*) *(.*)/";
    public int $number;

    public function getIdentifier(): string
    {
        return "c";
    }

    public function preProcess(string $input): string
    {
        $matches = [];
        preg_match(self::$regex, $input, $matches);

        if (!empty($matches)) {
            if (self::isNullOrWhiteSpace($matches[1])) {
                $this->number = 0;
            } else {
                $this->number = $matches[1];
            }

            if (self::isNullOrWhiteSpace($matches[2])) {
                return "";
            }

            return rtrim($matches[2]);
        }

        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            MMarker::class,
            MSMarker::class,
            SMarker::class,
            BMarker::class,
            DMarker::class,
            VMarker::class,
            PMarker::class,
            PCMarker::class,
            CDMarker::class,
            CPMarker::class,
            CLMarker::class,
            QMarker::class,
            QSMarker::class,
            QSEndMarker::class,
            QAMarker::class,
            QMarker::class,
            NBMarker::class,
            RMarker::class,
            LIMarker::class,
            TableBlock::class,
            MMarker::class,
            MIMarker::class,
            PIMarker::class,
            CAMarker::class,
            CAEndMarker::class,
            SPMarker::class,
            TextBlock::class,
            REMMarker::class,
            DMarker::class,
            VAMarker::class,
            VAEndMarker::class,
            FMarker::class,
            FEndMarker::class
        ];
    }

    public function getPublishedChapterMarker(): string
    {
        $childCharacterMarkers = $this->getChildMarkers(CPMarker::class);
        if (!empty($childCharacterMarkers)) {
            /** @var CPMarker $marker */
            $marker = $childCharacterMarkers[0];
            return $marker->publishedChapterMarker;
        } else {
            return strval($this->number);
        }
    }

    public function customChapterLabel()
    {
        $childChapLabelMarker = $this->getChildMarkers(CLMarker::class);
        if (!empty($childChapLabelMarker)) {
            /** @var CLMarker $marker */
            $marker = $childChapLabelMarker[0];
            return $marker->label;
        } else {
            return $this->getPublishedChapterMarker();
        }
    }
}