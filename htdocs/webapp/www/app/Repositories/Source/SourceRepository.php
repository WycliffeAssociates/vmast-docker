<?php


namespace App\Repositories\Source;


use App\Models\ORM\Source;
use Database\QueryException;

class SourceRepository implements ISourceRepository
{
    protected $source = null;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function create($data)
    {
        $source = new Source($data);
        $source->save();
        return $source;
    }

    public function get($id)
    {
        return $this->source::find($id);
    }

    public function getByLangAndSlug($langID, $slug) {
        return $this->source::where("langId", $langID)->where("slug", $slug)->first();
    }

    public function getWith($relation)
    {
        return $this->source::with($relation)->get();
    }

    public function delete(&$self)
    {
        $self->delete();
    }

    public function save(&$self)
    {
        $self->save();
    }

    public function upsertAll($sources) {
        $dbSources = self::all();

        $ids = $dbSources->map(function($item) {
            return "{$item->langID}_{$item->slug}";
        })->toArray();

        $sourcesToInsert = array_filter($sources, function ($item) use ($ids) {
            $id = "${item["langID"]}_${item["slug"]}";
            return !in_array($id, $ids);
        });

        foreach ($sourcesToInsert as $source) {
            try {
                self::create([
                    "langID" => $source["langID"],
                    "slug" => $source["slug"],
                    "name" => $source["name"]
                ]);
            } catch (QueryException $e) {
            }
        }

        foreach ($sources as $src) {
            $source = self::getByLangAndSlug($src["langID"], $src["slug"]);
            if ($source) {
                $source->name = $src["name"];
                $source->save();
            }
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->source, $method], $args);
    }
}