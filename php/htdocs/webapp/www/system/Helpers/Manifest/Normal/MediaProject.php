<?php

namespace Helpers\Manifest\Normal;

class MediaProject {
    private $identifier;
    private $version;
    private $media;

    /**
     * @param string $identifier
     * @param string $version
     * @param MediaType[] $media
     */
    function __construct(string $identifier, string $version, array $media) {
        $this->identifier = $identifier;
        $this->version = $version;
        $this->media = $media;
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getMedia(): array {
        return $this->media;
    }
}