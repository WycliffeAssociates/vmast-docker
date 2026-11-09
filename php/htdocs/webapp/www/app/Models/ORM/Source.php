<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Source extends Model
{
    protected $guarded = array("srcID");
    protected $primaryKey = 'srcID';
    public $timestamps  = false;

    public function language() {
        return $this->belongsTo(Language::class, "langID", "langID");
    }
}