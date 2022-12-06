<?php

namespace Helpers\Manifest\Tstudio;

class Generator
{
    private $name;
    private $build;

    /**
     * Generator constructor.
     * @param string $name
     * @param string $build
     */
    function __construct($name, $build) {
        $this->name = $name;
        $this->build = $build;
    }

    public function name()
    {
        return $this->name;
    }

    public function build()
    {
        return $this->build;
    }
}