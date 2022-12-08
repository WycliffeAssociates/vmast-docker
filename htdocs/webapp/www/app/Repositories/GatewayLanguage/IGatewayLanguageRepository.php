<?php


namespace App\Repositories\GatewayLanguage;


interface IGatewayLanguageRepository
{
    public function create($data);

    public function get($id);

    public function getWith($relation);

    public function delete(&$self);

    public function save(&$self);
}