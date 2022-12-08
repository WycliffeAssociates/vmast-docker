<?php


namespace Helpers;


class ProjectFile
{
    private $absPath;
    private $relPath;
    private $content;
    private $isFromDisk;

    public function __construct() {

    }

    public static function withFile($relPath, $absPath)
    {
        $instance = new self();
        $instance->relPath = $relPath;
        $instance->absPath = $absPath;
        $instance->isFromDisk = true;
        return $instance;
    }

    public static function withContent($relPath, $content)
    {
        $instance = new self();
        $instance->relPath = $relPath;
        $instance->content = $content;
        $instance->isFromDisk = false;
        return $instance;
    }


    public function absPath()
    {
        return $this->absPath;
    }

    public function relPath()
    {
        return $this->relPath;
    }

    public function content()
    {
        return $this->content;
    }

    public function isFromDisk()
    {
        return $this->isFromDisk;
    }
}