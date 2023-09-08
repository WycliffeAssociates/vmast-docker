<?php


namespace App\Repositories\Translation;


use App\Models\ORM\Translation;

class TranslationRepository implements ITranslationRepository {

    protected $translation = null;

    public function __construct(Translation $translation) {
        $this->translation = $translation;
    }

    public function create($data) {
        $translation = new Translation($data);
        $translation->save();
        return $translation;
    }

    public function get($id) {
        return $this->translation::find($id);
    }

    public function getWith($relation) {
        return $this->translation::with($relation)->get();
    }

    public function delete(&$self) {
        $self->delete();
    }

    public function save(&$self) {
        $self->save();
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->translation, $method], $args);
    }
}