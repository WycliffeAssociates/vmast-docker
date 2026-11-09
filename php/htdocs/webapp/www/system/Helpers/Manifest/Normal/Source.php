<?php

namespace Helpers\Manifest\Normal;

class Source
{
    private $identifier;
    private $language;
    private $version;

    /**
     * Source constructor.
     * @param string $identifier
     * @param string $language
     * @param string $version
     */
    function __construct($identifier, $language, $version) {
        $this->identifier = $identifier;
        $this->language = $language;
        $this->version = $version;
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function language()
    {
        return $this->language;
    }

    public function version()
    {
        return $this->version;
    }
}