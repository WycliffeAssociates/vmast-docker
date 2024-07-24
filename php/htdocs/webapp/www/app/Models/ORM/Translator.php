<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Translator extends Model
{
    protected $guarded = array("trID", "memberID", "eventID");
    protected $primaryKey = 'trID';
    public $timestamps  = false;

    public function event() {
        return $this->belongsTo(Event::class, "eventID", "eventID");
    }

    public function member() {
        return $this->belongsTo(Member::class, "memberID", "memberID");
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, "trID", "trID");
    }

    public function wordGroup()
    {
        return $this->hasOne(WordGroup::class, "groupID", "currentChapter");
    }

    public function word()
    {
        return $this->hasOne(Word::class, "wordID", "currentChapter");
    }
}