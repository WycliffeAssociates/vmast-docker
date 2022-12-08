<?php

namespace App\Data\Resource;

class ResourceChapter
{
    public $chapter;
    public $chunks;

    public function __construct($chapter, $chunks)
    {
        $this->chapter = $chapter;
        $this->chunks = $chunks;
    }
}