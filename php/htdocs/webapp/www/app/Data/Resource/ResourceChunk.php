<?php

namespace App\Data\Resource;

class ResourceChunk
{
    public $type;
    public $text;
    public $meta;

    public function __construct($type, $text, $meta = null)
    {
        $this->type = $type;
        $this->text = $text;
        $this->meta = $meta;
    }
}