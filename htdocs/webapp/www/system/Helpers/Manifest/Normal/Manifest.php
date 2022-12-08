<?php


namespace Helpers\Manifest\Normal;

class Manifest {
    private $conformsTo;
    private $contributor;
    private $creator;
    private $description;
    private $format;
    private $identifier;
    private $issued;
    private $modified;
    /** @var Language[] */
    private $language;
    private $publisher;
    private $relation;
    private $rights;
    /** @var Source[] */
    private $source;
    private $subject;
    private $title;
    private $type;
    private $version;
    private $checkingEntity;
    private $checkingLevel;
    /** @var Project[] */
    private $projects;

    function __construct() {
        $this->conformsTo = "rc0.2";
        $this->contributor = [];
        $this->creator = "";
        $this->description = "";
        $this->format = "";
        $this->identifier = "";
        $this->issued = "";
        $this->modified = "";
        $this->language = new Language("", "", "");
        $this->publisher = "";
        $this->relation = [];
        $this->rights = "CC BY-SA 4.0";
        $this->source = [];
        $this->subject = "";
        $this->title = "";
        $this->type = "";
        $this->version = "1";
        $this->checkingEntity = [];
        $this->checkingLevel = "1";
        $this->projects = [];
    }

    public function getConformsTo(): string {
        return $this->conformsTo;
    }

    public function setConformsTo($conformsTo) {
        $this->conformsTo = $conformsTo;
    }

    public function getContributor(): array {
        return $this->contributor;
    }

    public function setContributor($contributors) {
        $this->contributor = $contributors;
    }

    public function addContributor($contributor) {
        if(!in_array($contributor, $this->contributor)) {
            $this->contributor[] = $contributor;
        }
    }

    public function getCreator(): string {
        return $this->creator;
    }

    public function setCreator($creator) {
        $this->creator = $creator;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getFormat(): string {
        return $this->format;
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function getIssued(): string {
        return $this->issued;
    }

    public function setIssued($issued) {
        $this->issued = $issued;
    }

    public function getModified(): string {
        return $this->modified;
    }

    public function setModified($modified) {
        $this->modified = $modified;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function getPublisher(): string {
        return $this->publisher;
    }

    public function setPublisher($publisher) {
        $this->publisher = $publisher;
    }

    public function getRelation(): array {
        return $this->relation;
    }

    public function setRelation($relations) {
        $this->relation = $relations;
    }

    public function getRights(): string {
        return $this->rights;
    }

    public function setRights($rights) {
        $this->rights = $rights;
    }

    public function getSource(): array {
        return $this->source;
    }

    public function setSource($sources) {
        $this->source = $sources;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function getCheckingEntity(): array {
        return $this->checkingEntity;
    }

    public function setCheckingEntity($entities) {
        $this->checkingEntity = $entities;
    }

    public function getCheckingLevel(): string {
        return $this->checkingLevel;
    }

    public function setCheckingLevel($level) {
        $this->checkingLevel = $level;
    }

    public function getProjects(): array {
        return $this->projects;
    }

    public function setProjects($projects) {
        $this->projects = $projects;
    }

    public function addProject($project) {
        $this->projects[] = $project;
    }

    public function getProject($identifier) {
        foreach ($this->projects as $project) {
            if($project->identifier() == $identifier) {
                return $project;
            }
        }
        return false;
    }

    public function output(): array {
        return [
            "dublin_core" => [
                "conformsto" => $this->conformsTo,
                "contributor" => $this->contributor,
                "creator" => $this->creator,
                "description" => $this->description,
                "format" => $this->format,
                "identifier" => $this->identifier,
                "issued" => $this->issued,
                "modified" => $this->modified,
                "language" => [
                    "direction" => $this->language->direction(),
                    "identifier" => $this->language->identifier(),
                    "title" => $this->language->title()
                ],
                "publisher" => $this->publisher,
                "relation" => $this->relation,
                "rights" => $this->rights,
                "source" => array_map(function ($source)
                {
                    return [
                        "identifier" => $source->identifier(),
                        "language" => $source->language(),
                        "version" => $source->version()
                    ];
                }, $this->source),
                "subject" => $this->subject,
                "title" => $this->title,
                "type" => $this->type,
                "version" => $this->version

            ],
            "checking" => [
                "checking_entity" => $this->checkingEntity,
                "checking_level" => $this->checkingLevel
            ],

            "projects" => array_map(function ($project)
            {
                return [
                    "title" => $project->title(),
                    "versification" => $project->versification(),
                    "identifier" => $project->identifier(),
                    "sort" => $project->sort(),
                    "path" => $project->path(),
                    "categories" => $project->categories()
                ];
            }, $this->projects)
        ];
    }
}