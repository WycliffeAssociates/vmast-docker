<?php

namespace Helpers\Manifest;

use Helpers\Manifest\Normal\Media;
use Helpers\Manifest\Normal\MediaProject;
use Helpers\Manifest\Normal\MediaResource;
use Helpers\Manifest\Normal\MediaType;

class MediaMapper {
    static function toMedia($array): Media {
        return new Media(
            self::mediaResourceFromArray($array["resource"] ?? []),
            self::mediaProjectsFromArray($array["projects"] ?? [])
        );
    }

    static function toArray(Media $media): array {
        return [
            "resource" => self::mediaResourceFromMedia($media->getResource()),
            "projects" => self::mediaProjectsFromMedia($media->getProjects())
        ];
    }

    private static function mediaResourceFromArray($data): MediaResource {
        return new MediaResource(
            $data["version"] ?? "",
            self::mediaTypeFromArray($data["media"] ?? [])
        );
    }

    private static function mediaResourceFromMedia(MediaResource $resource): array {
        return [
            "version" => $resource->getVersion(),
            "media" => self::mediaTypeFromMedia($resource->getMedia())
        ];
    }

    private static function mediaTypeFromArray(array $data): array {
        $mediaArray = [];
        foreach ($data as $media) {
            $mediaArray[] = new MediaType(
                $media["identifier"] ?? "",
                $media["version"] ?? "",
                $media["url"] ?? "",
                $media["quality"] ?? [],
                $media["chapter_url"] ?? "",
                $media["contributor"] ?? []
            );
        }
        return $mediaArray;
    }

    /**
     * @param MediaType[] $mediaTypes
     * @return array
     */
    private static function mediaTypeFromMedia(array $mediaTypes): array {
        $mediaArray = [];
        foreach ($mediaTypes as $media) {
            $mediaArray[] = [
                "identifier" => $media->getIdentifier(),
                "version" => $media->getVersion(),
                "url" => $media->getUrl(),
                "quality" => $media->getQuality(),
                "chapter_url" => $media->getChapterUrl(),
                "contributor" => $media->getContributor()
            ];
        }
        return $mediaArray;
    }

    private static function mediaProjectsFromArray($data): array {
        $projects = [];
        foreach ($data as $project) {
            $projects[] = new MediaProject(
                $project["identifier"] ?? "",
                $project["version"] ?? "",
                self::mediaTypeFromArray($project["media"] ?? [])
            );
        }
        return $projects;
    }

    /**
     * @param MediaProject[] $projects
     * @return array
     */
    private static function mediaProjectsFromMedia(array $projects): array {
        $projectsArray = [];
        foreach ($projects as $project) {
            $projectsArray[] = [
                "identifier" => $project->getIdentifier(),
                "version" => $project->getVersion(),
                "media" => self::mediaTypeFromMedia($project->getMedia())
            ];
        }
        return $projectsArray;
    }
}