<?php

namespace Helpers\Manifest\Normal;

class MediaResource {
    private $version;
    private $media;

    /**
     * @param string $version
     * @param MediaType[] $media
     */
    function __construct(string $version = "{latest}", array $media = []) {
        $this->version = $version;
        $this->media = $media;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getMedia(): array {
        return $this->media;
    }
}