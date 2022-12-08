<?php

namespace Helpers\Manifest;

use File;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class ManifestParser {
    private $manifestFile;
    private $mediaFile;
    private $media;

    function __construct($dir) {
        $this->manifestFile = $this->findFile("manifest.yaml", $dir);
        $this->mediaFile = $this->findFile("media.yaml", $dir);
    }

    function parseManifest(): ?Normal\Manifest {
        if (!$this->manifestFile) return null;

        $contents = File::get($this->manifestFile);
        $manifest = Yaml::parse($contents);
        return ManifestMapper::toManifest($manifest);
    }

    function parseMedia(): ?Normal\Media {
        if (!$this->mediaFile) return null;

        $contents = File::get($this->mediaFile);
        $mediaArray = Yaml::parse($contents);
        $media = MediaMapper::toMedia($mediaArray);
        $this->media = $media;
        return $media;
    }

    function writeMedia() {
        if ($this->mediaFile && $this->media) {
            $array = MediaMapper::toArray($this->media);
            $yaml = Yaml::dump($array, 10);
            File::put($this->mediaFile, $yaml);
        }
    }

    private function findFile($fileName, $dir): ?SplFileInfo {
        if (!File::exists($dir)) return null;

        $files = File::allFiles($dir);
        foreach ($files as $file) {
            if ($fileName == $file->getFilename()) {
                return $file;
            }
        }
        return null;
    }
}