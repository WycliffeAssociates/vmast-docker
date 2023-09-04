<?php

namespace App\Repositories\Translation;

interface ITranslationRepository
{
    public function create($data);

    public function get($id);

    public function getWith($relation);

    public function delete(&$self);

    public function save(&$self);
}