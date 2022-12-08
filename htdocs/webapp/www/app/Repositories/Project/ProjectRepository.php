<?php


namespace App\Repositories\Project;


use App\Models\ORM\Project;

class ProjectRepository implements IProjectRepository
{

    protected $project = null;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function create($data, $gatewayLanguage)
    {
        $project = new Project($data);
        $project->gatewayLanguage()->associate($gatewayLanguage)->save();
        return $project;
    }

    public function get($id)
    {
        return $this->project::find($id);
    }

    public function getWith($relation)
    {
        return $this->project::with($relation)->get();
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
        return call_user_func_array([$this->project, $method], $args);
    }
}