<?php

namespace Helpers\Manifest\Normal;

class Project
{
    private $title;
    private $versification;
    private $identifier;

    private $sort;
    private $path;
    private $categories;

    /**
     * Project constructor.
     * @param string $title
     * @param string $versification
     * @param string $identifier
     * @param integer $sort
     * @param string $path
     * @param array $categories
     */
    function __construct($title, $versification, $identifier, $sort, $path, $categories) {
        $this->title = $title;
        $this->versification = $versification;
        $this->identifier = $identifier;
        $this->sort = $sort;
        $this->path = $path;
        $this->categories = $categories;
    }

    public function title()
    {
        return $this->title;
    }

    public function versification()
    {
        return $this->versification;
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function sort()
    {
        return $this->sort;
    }

    public function path()
    {
        return $this->path;
    }

    public function categories()
    {
        return $this->categories;
    }
}