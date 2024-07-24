<?php

namespace App\Repositories\Preferences;

interface IPreferenceRepository {

    public function create($data);

    public function get($id);

    public function getByKey($key);

    public function delete(&$self);

    public function save(&$self);

    public function langNamesUrl();

    public function setLangNamesUrl($url);

    public function catalogUrl();

    public function setCatalogUrl($url);
}