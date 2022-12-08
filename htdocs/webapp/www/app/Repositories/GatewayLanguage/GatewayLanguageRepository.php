<?php


namespace App\Repositories\GatewayLanguage;


use App\Models\ORM\GatewayLanguage;

class GatewayLanguageRepository implements IGatewayLanguageRepository
{
    protected $glProject = null;

    public function __construct(GatewayLanguage $glProject)
    {
        $this->glProject = $glProject;
    }

    public function create($data)
    {
        $glProject = new GatewayLanguage($data);
        $glProject->save();
        return $glProject;
    }

    public function get($id)
    {
        return $this->glProject::find($id);
    }

    public function getWith($relation)
    {
        return $this->glProject::with($relation)->get();
    }

    public function delete(&$self)
    {
        $self->delete();
    }

    public function save(&$self)
    {
        $self->save();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->glProject, $method], $args);
    }
}