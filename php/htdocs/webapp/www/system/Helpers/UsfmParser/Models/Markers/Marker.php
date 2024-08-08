<?php

namespace Helpers\UsfmParser\Models\Markers;

use SplStack;

abstract class Marker
{
    /**
     * @var Marker[]
     */
    public array $contents;
    public int $position;

    public function __construct()
    {
        $this->contents = [];
    }

    public function getIdentifier(): string
    {
        return "";
    }

    /**
     * @return string[]
     */
    public function getAllowedContents(): array
    {
        return [];
    }

    /**
     * Pre-process the text contents before creating text elements inside of it
     * @param string $input
     * @return string
     */
    public function preProcess(string $input): string
    {
        return $input;
    }

    public function tryInsert(Marker $input): bool
    {
        if (!empty($this->contents) && $this->contents[sizeof($this->contents) - 1]->tryInsert($input))
        {
            return true;
        }

        if (in_array($input::class, $this->getAllowedContents())) {
            $this->contents[] = $input;
            return true;
        }
        return false;
    }

    /**
     * @return string[]
     */
    public function getTypesPathToLastMarker(): array
    {
        $types = [];
        $types[] = get_class($this);
        if (!empty($this->contents)) {
            $types = array_merge($types, $this->contents[sizeof($this->contents) - 1]->getTypesPathToLastMarker());
        }
        return $types;
    }

    /**
     * @param Marker $target
     * @return Marker[]
     */
    public function getHierarchyToMarker(Marker $target): array {
        $parents = new SplStack();
        $childMarkerContentsCount = 0;

        $found = false;
        $stack = new SplStack();
        $stack->push([$this, false]);

        while (!$stack->isEmpty()) {
            list($marker, $isLastInParent) = $stack->pop();
            if ($marker == $target) {
                $found = true;
                break;
            }
            if ($marker instanceof Marker && !empty($marker->contents)) {
                $parents->push([$marker, $isLastInParent]);
                $childMarkerContentsCount = sizeof($marker->contents);
                for ($i = 0; $i < $childMarkerContentsCount; $i++) {
                    $stack->push([$marker->contents[$i], $i == 0]);
                }
            }
            elseif ($isLastInParent)
            {
                list(, $isLast) = $parents->pop();
                while ($isLast) {
                    list(, $isLast) = $parents->pop();
                }
            }
        }

        if (!$found) return [];
        $output = [];
        $output[] = $target;
        $output = array_merge(
            array_map(function ($i) {
                list($marker,) = $i;
                return $marker;
            }, (array)$parents),
        $output);

        return array_reverse($output);
    }

    /**
     * Get the paths to multiple markers
     * @param Marker[] $targets
     * @return array{Marker, Marker[]}
     */
    public function getHierarchyToMultipleMarkers(array $targets): array
    {
        $output = [];
        $parents = new SplStack();
        $childMarkerContentsCount = 0;

        $stack = new SplStack();
        $stack->push([$this, false]);

        while (!$stack->isEmpty()) {
            list($marker, $isLastInParent) = $stack->pop();
            if (in_array($marker, $targets)) {
                $tmp = [$marker];
                $tmp = array_merge(
                    array_map(function ($i) {
                        list($marker,) = $i;
                        return $marker;
                        }, (array)$parents),
                    $tmp
                );
                $tmp = array_reverse($tmp);
                $output[] = [$marker, $tmp];
                if (sizeof($output) == sizeof($targets)) {
                    break;
                }
            }

            if ($marker instanceof Marker && !empty($marker->contents)) {
                $parents->push([$marker, $isLastInParent]);
                $childMarkerContentsCount = sizeof($marker->contents);
                for ($i = 0; $i < $childMarkerContentsCount; $i++) {
                    $stack->push([$marker->contents[$i], $i == 0]);
                }
            }
            elseif ($isLastInParent) {
                list(, $isLast) = $parents->pop();
                while ($isLast) {
                    list(, $isLast) = $parents->pop();
                }
            }
        }

        foreach ($targets as $i) {
            if (!in_array($i, $output)) {
                $output[] = [$i, []];
            }
        }

        return $output;
    }

    /**
     * A recursive search for children of a certain type
     * @param string $type
     * @param string[]|null $ignoredParents
     * @return Marker[]
     */
    public function getChildMarkers(string $type, array $ignoredParents = null): array
    {
        $output = [];
        $stack = new SplStack();

        if (isset($ignoredParents) && in_array(get_class($this), $ignoredParents)) {
            return $output;
        }

        $stack->push($this);

        while (!$stack->isEmpty()) {
            $marker = $stack->pop();
            if ($marker::class == $type) {
                $output[] = $marker;
            }
            foreach ($marker->contents as $child) {
                if (!isset($ignoredParents) || !in_array($child::class, $ignoredParents)) {
                    $stack->push($child);
                }
            }
        }

        return array_reverse($output);
    }

    /**
     * @return Marker
     */
    public function getLastDescendent(): Marker
    {
        if (empty($this->contents)) return $this;

        return $this->contents[sizeof($this->contents) - 1]->getLastDescendent();
    }

    public static function isNullOrWhiteSpace($str): bool
    {
        return !isset($str) || trim($str) === '';
    }
}