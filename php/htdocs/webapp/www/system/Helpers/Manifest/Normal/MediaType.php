<?php

namespace Helpers\Manifest\Normal;

class MediaType {
    private $identifier;
    private $version;
    private $url;
    private $quality;
    private $chapterUrl;
    private $contributor;

    /**
     * @param string $identifier
     * @param string $version
     * @param string $url
     * @param array $quality
     * @param string $chapterUrl
     * @param array $contributor
     */
    function __construct($identifier, $version, $url, $quality, $chapterUrl, $contributor) {
        $this->identifier = $identifier;
        $this->version = $version;
        $this->url = $url;
        $this->quality = $quality;
        $this->chapterUrl = $chapterUrl;
        $this->contributor = $contributor;
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

     public function getVersion(): string {
        return $this->version;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getQuality(): array {
        return $this->quality;
    }

    public function getChapterUrl(): string {
        return $this->chapterUrl;
    }

    public function getContributor(): array {
        return $this->contributor;
    }
}