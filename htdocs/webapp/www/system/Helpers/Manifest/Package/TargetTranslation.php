<?php

namespace Helpers\Manifest\Package;

class TargetTranslation
{
    private $path;
    private $id;
    private $commitHash;
    private $direction;

    /**
     * TargetTranslation constructor.
     * @param string $path
     * @param string $id
     * @param CommitHash $commitHash
     * @param string $direction
     */
    function __construct($path, $id, $commitHash, $direction) {
        $this->path = $path;
        $this->id = $id;
        $this->commitHash = $commitHash;
        $this->direction = $direction;
    }

    public function path()
    {
        return $this->path;
    }

    public function id()
    {
        return $this->id;
    }

    public function commitHash()
    {
        return $this->commitHash;
    }

    public function direction()
    {
        return $this->direction;
    }
}