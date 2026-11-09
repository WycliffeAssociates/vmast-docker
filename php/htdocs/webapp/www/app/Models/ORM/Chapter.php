<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Chapter extends Model
{
    protected $fillable = array();
    protected $primaryKey = 'chapterID';
    public $timestamps  = false;

    public function translator() {
        return $this->belongsTo(Translator::class, "trID", "trID");
    }

    public function checkerL2() {
        return $this->belongsTo(CheckerL2::class, "l2chID", "l2chID");
    }

    public function checkerL3() {
        return $this->belongsTo(CheckerL3::class, "l3chID", "l3chID");
    }

    public function wordGroup() {
        return $this->hasOne(WordGroup::class, "groupID", "chapter");
    }

    public function word() {
        return $this->hasOne(Word::class, "wordID", "chapter");
    }
}