<?php


namespace App\Repositories\Language;


interface ILanguageRepository
{
    public function create($data);

    public function get($id);

    public function getGwLanguages();

    public function getTargetLanguages();

    public function getLanguagesByGl($gwLangName);

    public function getByName($langName);

    public function getWith($relation);

    public function delete(&$self);

    public function save(&$self);

    public function upsertAll($languages);
}