<?php

namespace Helpers\Manifest\Package;

class CommitHash
{
    private $stdout;
    private $stderr;
    private $error;

    /**
     * CommitHash constructor.
     * @param string $stdout
     * @param string $stderr
     * @param string $error
     */
    function __construct($stdout, $stderr, $error) {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
        $this->error = $error;
    }

    public function stdout()
    {
        return $this->stdout;
    }

    public function stderr()
    {
        return $this->stderr;
    }

    public function error()
    {
        return $this->error;
    }
}