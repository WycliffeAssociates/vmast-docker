<?php

namespace Helpers\Manifest\Tstudio;

class BasicCategory
{
    private $id;
    private $name;

    /**
     * BasicCategory constructor.
     * @param string $id
     * @param string $name
     */
    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }
}