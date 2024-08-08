<?php

namespace Helpers\UsfmParser\Models\Markers;

/**
 * Wordlist / Glossary / Dictionary Entry Marker
 */
class WMarker extends Marker
{
    public string $term;
    /**
     * @var array{string, string}
     */
    public array $attributes;

    private static string $wordAttributePattern = "([\\w-]+)=?\"?([\\w,:.]*)\"?";

    public function getIdentifier(): string
    {
        return "w";
    }

    public function preProcess(string $input): string
    {
        $input = trim($input);
        $this->attributes = [];

        $wordEntry = explode("|", $input);
        $this->term = $wordEntry[0];

        if (sizeof($wordEntry) > 1) {
            $wordAttr = explode(" ", $wordEntry[1]);
            foreach ($wordAttr as $attr) {
                preg_match(self::$wordAttributePattern, $attr, $matches);

                if (strlen($matches[2]) == 0) {
                    $this->attributes["lemma"] = $matches[1];
                } else {
                    $this->attributes[$matches[1]] = $matches[2];
                }
            }
        }

        return "";
    }

    public function getAllowedContents(): array
    {
        return [TextBlock::class];
    }
}