<?php

namespace Helpers\Manifest\Normal;

class Media {
    private $resource;
    private $projects;

    /**
     * @param MediaResource $resource
     * @param MediaProject[] $projects
     */
    function __construct(MediaResource $resource, array $projects) {
        $this->resource = $resource;
        $this->projects = $projects;
    }

    public function getResource(): MediaResource {
        return $this->resource;
    }

    public function setResource($resource): void {
        $this->resource = $resource;
    }

    public function getProjects(): array {
        return $this->projects;
    }

    public function setProjects($projects): void {
        $this->projects = $projects;
    }
}