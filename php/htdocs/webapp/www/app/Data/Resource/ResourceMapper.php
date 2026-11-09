<?php

namespace App\Data\Resource;

use Support\Collection;

class ResourceMapper
{
    public static function fromResource($bc) {
        return $bc->map(function($item) {
            return ResourceMapper::fromChapter($item);
        })->toArray();
    }

    public static function toResource($array) {
        $chapters = array_map(function ($item) {
            return ResourceMapper::toChapter($item);
        }, $array);
        return new Collection($chapters);
    }

    private static function fromChunk($chunk) {
        return [
            "type" => $chunk->type,
            "text" => $chunk->text ?? "",
            "meta" => $chunk->meta ?? ""
        ];
    }

    private static function toChunk($data) {
        return new ResourceChunk(
            $data["type"],
            !empty($data["text"]) ? $data["text"] : null,
            !empty($data["meta"]) ? $data["meta"] : null
        );
    }

    private static function fromChapter($chapter) {
        return [
            "chapter" => $chapter->chapter,
            "chunks" => $chapter->chunks->map(function($item) {
                return ResourceMapper::fromChunk($item);
            })->toArray()
        ];
    }

    private static function toChapter($data) {
        $chunksArr = array_map(function($item) {
            return ResourceMapper::toChunk($item);
        }, $data["chunks"]);
        $chunks = new Collection($chunksArr);

        return new ResourceChapter($data["chapter"], $chunks);
    }
}