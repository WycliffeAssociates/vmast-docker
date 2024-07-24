<?php

namespace Helpers\Manifest\Tstudio;

class SourceTranslation
{
    private $languageId;
    private $resourceId;
    private $checkingLevel;
    private $dateModified;
    private $version;

    /**
     * SourceTranslation constructor.
     * @param string $languageId
     * @param string $resourceId
     * @param string $checkingLevel
     * @param string $dateModified
     * @param string $version
     */
    function __construct($languageId, $resourceId, $checkingLevel, $dateModified, $version) {
        $this->languageId = $languageId;
        $this->resourceId = $resourceId;
        $this->checkingLevel = $checkingLevel;
        $this->dateModified = $dateModified;
        $this->version = $version;
    }

    public function languageId()
    {
        return $this->languageId;
    }

    public function resourceId()
    {
        return $this->resourceId;
    }

    public function checkingLevel()
    {
        return $this->checkingLevel;
    }

    public function dateModified()
    {
        return $this->dateModified;
    }

    public function version()
    {
        return $this->version;
    }
}