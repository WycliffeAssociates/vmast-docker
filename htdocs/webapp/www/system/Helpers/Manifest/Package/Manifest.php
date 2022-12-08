<?php


namespace Helpers\Manifest\Package;

class Manifest
{
    /** @var Generator */
    private $generator;
    private $packageVersion;
    private $timestamp;
    /** @var TargetTranslation[] */
    private $targetTranslations;
    private $root;

    function __construct() {
        $this->generator = new Generator("ts-desktop", 1);
        $this->packageVersion = 2;
        $this->timestamp = 0;
        $this->targetTranslations = [];
    }

    public function setGenerator($generator)
    {
        $this->generator = $generator;
    }

    public function setPackageVersion($packageVersion)
    {
        $this->packageVersion = $packageVersion;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * SourceTranslations
     * @param TargetTranslation[] $targetTranslations
     */
    public function setTargetTranslations($targetTranslations)
    {
        $this->targetTranslations = $targetTranslations;
    }

    public function addTargetTranslation($targetTranslation)
    {
        if(!in_array($targetTranslation, $this->targetTranslations))
        {
            $this->targetTranslations[] = $targetTranslation;
        }
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getRoot()
    {
        return $this->root;
    }


    public function output()
    {
        return [
            "generator" => [
                "name" => $this->generator->name(),
                "build" => $this->generator->build()
            ],
            "package_version" => $this->packageVersion,
            "timestamp" => $this->timestamp,
            "target_translations" => array_map(function($targetTranslation) {
                return [
                    "path" => $targetTranslation->path(),
                    "id" => $targetTranslation->id(),
                    "commit_hash" => [
                        "stdout" => $targetTranslation->commitHash()->stdout(),
                        "stderr" => $targetTranslation->commitHash()->stderr(),
                        "error" => $targetTranslation->commitHash()->error(),
                    ],
                    "direction" => $targetTranslation->direction(),
                ];
            }, $this->targetTranslations),
        ];
    }
}
