<?php


namespace App\Repositories\Project;


interface IProjectRepository
{
    public function create($data, $gatewayLanguage);

    public function get($id);

    public function getWith($relation);

    public function delete(&$self);

    public function save(&$self);
}