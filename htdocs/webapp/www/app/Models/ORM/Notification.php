<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Notification extends Model
{
    protected $guarded = array("noteID", "toMemberID", "fromMemberID", "eventID");
    protected $primaryKey = 'noteID';
    public $timestamps  = false;

    public function event() {
        return $this->belongsTo(Event::class, "eventID", "eventID");
    }

    public function fromMember() {
        return $this->belongsTo(Member::class, "fromMemberID", "memberID");
    }

    public function toMember() {
        return $this->belongsTo(Member::class, "toMemberID", "memberID");
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