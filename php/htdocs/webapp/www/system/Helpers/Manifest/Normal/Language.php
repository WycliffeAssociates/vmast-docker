<?php

namespace Helpers\Manifest\Normal;

class Language
{
    private $direction;
    private $identifier;
    private $title;

    /**
     * Language constructor.
     * @param string $direction
     * @param string $identifier
     * @param string $title
     */
    function __construct($direction, $identifier, $title) {
        $this->direction = $direction;
        $this->identifier = $identifier;
        $this->title = $title;
    }

    public function direction()
    {
        return $this->direction;
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function title()
    {
        return $this->title;
    }
}