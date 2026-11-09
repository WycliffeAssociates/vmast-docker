<?php

namespace Helpers\Manifest\Tstudio;

class TargetLanguage extends BasicCategory
{
    private $direction;

    /**
     * TargetLanguage constructor.
     * @param string $id
     * @param string $name
     * @param string $direction
     */
    function __construct($id, $name, $direction) {
        parent::__construct($id, $name);
        $this->direction = $direction;
    }

    public function direction()
    {
        return $this->direction;
    }
}