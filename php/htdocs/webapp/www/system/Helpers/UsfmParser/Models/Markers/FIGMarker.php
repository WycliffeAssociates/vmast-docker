<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Wordlist / Glossary / Dictionary Entry Marker
 */
class FIGMarker extends Marker
{
    public string $caption;
    public string $description;
    public string $width;
    public string $location;
    public string $copyright;
    public string $reference;
    public string $filePath;

    public function getIdentifier(): string
    {
        return "fig";
    }

    public function preProcess(string $input): string
    {
        $input = trim($input);

        $wordEntry = explode("|", $input);

        if (!empty($wordEntry)) {
            $this->description = trim($wordEntry[0]);
        }
        if (sizeof($wordEntry) > 1) {
            $this->filePath = trim($wordEntry[1]);
        }
        if (sizeof($wordEntry) > 2) {
            $this->width = trim($wordEntry[2]);
        }
        if (sizeof($wordEntry) > 3) {
            $this->location = trim($wordEntry[3]);
        }
        if (sizeof($wordEntry) > 4) {
            $this->copyright = trim($wordEntry[4]);
        }
        if (sizeof($wordEntry) > 5) {
            $this->caption = trim($wordEntry[5]);
        }
        if (sizeof($wordEntry) > 6) {
            $this->reference = trim($wordEntry[6]);
        }

        $contentArr = explode("|", $input);

        if (!empty($contentArr) && sizeof($contentArr) <= 2) {
            $this->caption = trim($contentArr[0]);
            $attributes = explode("\"", $contentArr[1]);

            for ($i = 0; $i < sizeof($attributes); $i++) {
                $attribute = str_replace(" ", "", $attributes[$i]);

                if (str_contains($attribute, "alt=")) {
                    $this->description = trim($attributes[$i + 1]);
                }
                if (str_contains($attribute, "src=")) {
                    $this->filePath = trim($attributes[$i + 1]);
                }
                if (str_contains($attribute, "size=")) {
                    $this->width = trim($attributes[$i + 1]);
                }
                if (str_contains($attribute, "loc=")) {
                    $this->location = trim($attributes[$i + 1]);
                }
                if (str_contains($attribute, "copy=")) {
                    $this->copyright = trim($attributes[$i + 1]);
                }
                if (str_contains($attribute, "ref=")) {
                    $this->reference = trim($attributes[$i + 1]);
                }
            }
        }

        return "";
    }
}