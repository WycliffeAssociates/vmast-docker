<?php

namespace App\Repositories\Preferences;

use App\Models\ORM\Preference;

class PreferenceRepository implements IPreferenceRepository
{
    protected $preference = null;

    private const PREF_KEY = "prefKey";
    private const PREF_VALUE = "prefValue";

    private const LANG_NAMES_URL_KEY = "langNamesUrl";
    private const DEFAULT_LANG_NAMES_URL = "https://td.unfoldingword.org/exports/langnames.json";

    private const CATALOG_URL_KEY = "catalogUrl";
    private const DEFAULT_CATALOG_URL = "https://api.bibletranslationtools.org/v3/catalog.json";

    public function __construct(Preference $preference) {
        $this->preference = $preference;
    }

    public function create($data) {
        $preference = new Preference($data);
        $preference->save();

        return $preference;
    }

    public function get($id) {
        return $this->preference::find($id);
    }

    public function getByKey($key) {
        return $this->preference::where("prefKey", $key)->first();
    }

    public function delete(&$self) {
        return $self->delete();
    }

    public function save(&$self) {
        return $self->save();
    }

    public function langNamesUrl() {
        $url = self::getByKey(self::LANG_NAMES_URL_KEY);
        if (!$url) {
            $url = self::create([
                    self::PREF_KEY => self::LANG_NAMES_URL_KEY,
                    self::PREF_VALUE => self::DEFAULT_LANG_NAMES_URL
                ]);
        }
        return $url;
    }

    public function setLangNamesUrl($url) {
        $pref = self::getByKey(self::LANG_NAMES_URL_KEY);
        if ($pref) {
            $pref->{self::PREF_VALUE} = $url;
            $pref->save();
        } else {
            $pref = self::create([
                self::PREF_KEY => self::LANG_NAMES_URL_KEY,
                self::PREF_VALUE => $url
            ]);
        }
        return $pref;
    }

    public function catalogUrl() {
        $url = self::getByKey(self::CATALOG_URL_KEY);
        if (!$url) {
            $url = self::create([
                self::PREF_KEY => self::CATALOG_URL_KEY,
                self::PREF_VALUE => self::DEFAULT_CATALOG_URL
            ]);
        }
        return $url;
    }

    public function setCatalogUrl($url) {
        $pref = self::getByKey(self::CATALOG_URL_KEY);
        if ($pref) {
            $pref->{self::PREF_VALUE} = $url;
            $pref->save();
        } else {
            $pref = self::create([
                self::PREF_KEY => self::CATALOG_URL_KEY,
                self::PREF_VALUE => $url
            ]);
        }
        return $pref;
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->preference, $method], array_values($args));
    }
}