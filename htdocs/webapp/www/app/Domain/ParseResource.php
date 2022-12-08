<?php

namespace App\Domain;

use App\Data\Resource\ResourceChapter;
use App\Data\Resource\ResourceChunk;
use App\Data\Resource\ResourceChunkType;
use Support\Collection;

class ParseResource
{
    public static function parse($md, $chapter, $combineImage = false) {
        $lines = preg_split("/\\r\\n|\\n|\\r/", $md);
        $chunks = new Collection();

        $tmpImg = null;
        foreach ($lines as $line) {
            $line = trim($line);
            $link = ParseResource::parseLink($line);
            $heading = ParseResource::parseHeading($line);
            $image = ParseResource::parseImage($line, $combineImage);
            $italic = ParseResource::parseItalic($line);
            $listItem = ParseResource::parseListItem($line);

            if ($heading) {
                $chunks->push($heading);
            } elseif ($link) {
                $chunks->push($link);
            } elseif ($italic) {
                $chunks->push($italic);
            } elseif ($image) {
                if ($combineImage) {
                    $tmpImg = $image;
                } else {
                    $chunks->push($image);
                }
            } elseif ($listItem) {
                $chunks->push($listItem);
            } elseif (!empty($line)) {
                if ($tmpImg) {
                    $tmpImg->text = $line;
                    $chunks->push($tmpImg);
                    $tmpImg = null;
                } else {
                    $chunks->push(
                        ParseResource::toChunk(ResourceChunkType::TEXT, $line)
                    );
                }
            }
        }

        return new ResourceChapter($chapter, $chunks);
    }

    private static function parseHeading($line) {
        $regex = "/^(#{1,6})\s(.*)/";
        $hasTitle = preg_match($regex, $line, $matches);
        if ($hasTitle && isset($matches[1]) && isset($matches[2])) {
            $type = ParseResource::headingType($matches[1]);
            return new ResourceChunk($type, $matches[2], "${matches[1]} {}");
        }
        return null;
    }

    private static function parseImage($line, $combineImage) {
        $regex = "/^!\[.*?\]\((.*?)\)/";
        $hasImage = preg_match($regex, $line, $matches);
        if ($hasImage) {
            if ($combineImage && isset($matches[1])) {
                return new ResourceChunk(ResourceChunkType::IMAGE, null, $matches[1]);
            } else {
                return new ResourceChunk(ResourceChunkType::IMAGE, null, $matches[0]);
            }
        }
        return null;
    }

    private static function parseLink($line) {
        $regex = "/([^!].*?)\[(.*?)\]\(.*?\)/";
        $hasLink = preg_match_all($regex, $line, $matches);
        if ($hasLink && isset($matches[1]) && isset($matches[2])) {
            $start = $matches[1][0];
            $divider = $matches[1][1] ?? "";
            $words = join($divider, $matches[2]);
            $text = $start . $words;

            return new ResourceChunk(ResourceChunkType::LINK, $text, $line);
        }
        return null;
    }

    private static function parseItalic($line) {
        $regex = "/^_(.*?)_$/";
        $hasItalic = preg_match($regex, $line, $matches);
        if ($hasItalic && isset($matches[1])) {
            return new ResourceChunk(ResourceChunkType::ITALIC, $matches[1], "_{}_");
        }
        return null;
    }

    private static function parseListItem($line) {
        $regex = "/^(\d+\.|-|\*)\s(.*)$/";
        $hasListItem = preg_match($regex, $line, $matches);
        if ($hasListItem && isset($matches[1]) && isset($matches[2])) {
            return new ResourceChunk(ResourceChunkType::LIST_ITEM, $matches[2], "${matches[1]} {}");
        }
        return null;
    }

    private static function toChunk($type, $text, $meta = null) {
        return new ResourceChunk($type, $text, $meta);
    }

    private static function headingType($tag) {
        $number = strlen($tag);
        switch ($number) {
            case 1:
                $type = ResourceChunkType::HEADING_1;
                break;
            case 2:
                $type = ResourceChunkType::HEADING_2;
                break;
            case 3:
                $type = ResourceChunkType::HEADING_3;
                break;
            case 4:
                $type = ResourceChunkType::HEADING_4;
                break;
            case 5:
                $type = ResourceChunkType::HEADING_5;
                break;
            case $type = ResourceChunkType::HEADING_6;
                break;
        }
        return $type;
    }
}