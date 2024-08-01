<?php


namespace App\Repositories\Language;


use App\Models\ORM\Language;
use Database\QueryException;

class LanguageRepository implements ILanguageRepository {
    protected $language = null;

    public function __construct(Language $language) {
        $this->language = $language;
    }

    public function create($data) {
        $language = new Language($data);
        $language->save();
        return $language;
    }

    public function get($id) {
        return $this->language::find($id);
    }

    public function getGwLanguages() {
        return $this->language->where("isGW", 1)->get();
    }

    public function getTargetLanguages() {
        return $this->language->where("isGW", 0)->get();
    }

    public function getLanguagesByGl($gwLangName) {
        return $this->language->where("gwLang", $gwLangName)->get();
    }

    public function getByName($langName) {
        return $this->language::where("langName", $langName)->first();
    }

    public function getWith($relation) {
        return $this->language::with($relation)->get();
    }

    public function delete(&$self) {
        return $self->delete();
    }

    public function save(&$self) {
        return $self->save();
    }

    public function upsertAll($languages) {
        $dbLanguages = self::all();
        $dbIds = $dbLanguages->map(function($item) {
            return $item->langID;
        })->toArray();
        $languagesToInsert = array_filter($languages, function($item) use($dbIds) {
            return !in_array($item->lc, $dbIds);
        });

        foreach ($languagesToInsert as $lang) {
            try {
                self::create([
                    "langID" => $lang->lc,
                    "langName" => $lang->ln,
                    "angName" => $lang->ang,
                    "isGW" => $lang->gw,
                    "direction" => $lang->ld
                ]);
            } catch (QueryException $e) {
            }
        }

        foreach ($languages as $lang) {
            $language = self::get($lang->lc);
            if ($language) {
                $language->langName = $lang->ln;
                $language->angName = $lang->ang;
                $language->isGW = $lang->gw;
                $language->direction = $lang->ld;
                $language->save();
            }
        }
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->language, $method], array_values($args));
    }
}