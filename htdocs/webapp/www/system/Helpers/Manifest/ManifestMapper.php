<?php

namespace Helpers\Manifest;

use Helpers\Manifest\Normal\Language;
use Helpers\Manifest\Normal\Manifest;
use Helpers\Manifest\Normal\Project;
use Helpers\Manifest\Normal\Source;

class ManifestMapper {

    public static function toManifest($array): Manifest {
        $manifest = new Manifest();
        $manifest->setContributor($array["dublin_core"]["contributor"] ?? []);
        $manifest->setCreator($array["dublin_core"]["creator"] ?? "");
        $manifest->setDescription($array["dublin_core"]["description"] ?? "");
        $manifest->setFormat($array["dublin_core"]["format"] ?? "");
        $manifest->setIdentifier($array["dublin_core"]["identifier"] ?? "");
        $manifest->setIssued($array["dublin_core"]["issued"] ?? "");
        $manifest->setLanguage(self::getLanguage($array["dublin_core"]["language"] ?? []));
        $manifest->setModified($array["dublin_core"]["modified"] ?? "");
        $manifest->setPublisher($array["dublin_core"]["publisher"] ?? "");
        $manifest->setRelation($array["dublin_core"]["relation"] ?? "");
        $manifest->setRights($array["dublin_core"]["rights"] ?? "");
        $manifest->setSource(self::getSource($array["dublin_core"]["source"] ?? []));
        $manifest->setSubject($array["dublin_core"]["subject"] ?? "");
        $manifest->setTitle($array["dublin_core"]["title"] ?? "");
        $manifest->setType($array["dublin_core"]["type"] ?? "");
        $manifest->setVersion($array["dublin_core"]["version"] ?? "");
        $manifest->setCheckingEntity($array["checking"]["checking_entity"] ?? "");
        $manifest->setCheckingLevel($array["checking"]["checking_level"] ?? "");
        $manifest->setProjects(self::getProject($array["projects"]) ?? []);

        return $manifest;
    }

    private static function getLanguage($data): Language {
        return new Language(
            $data["direction"] ?? "",
            $data["identifier"] ?? "",
            $data["title"] ?? ""
        );
    }

    private static function getSource($data): array {
        $sources = [];
        foreach ($data as $source) {
            $sources[] = new Source(
                $source["identifier"] ?? "",
                $source["language"] ?? "",
                $source["version"] ?? ""
            );
        }
        return $sources;
    }

    private static function getProject($data): array {
        $projects = [];
        foreach ($data as $project) {
            $projects[] = new Project(
                $project["title"] ?? "",
                $project["versification"] ?? "",
                $project["identifier"] ?? "",
                $project["sort"] ?? 0,
                $project["path"] ?? "",
                $project["categories"] ?? []
            );
        }
        return $projects;
    }
}