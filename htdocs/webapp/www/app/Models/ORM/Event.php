<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Event extends Model
{
    protected $guarded = array("eventID", "projectID");
    protected $primaryKey = 'eventID';
    public $timestamps  = false;

    public function admins()
    {
        return $this->belongsToMany(
            Member::class,
            "member_event",
            "eventID",
            "memberID"
        );
    }

    public function project() {
        return $this->belongsTo(Project::class, "projectID");
    }

    public function bookInfo() {
        return $this->belongsTo(BookInfo::class, "bookCode", "code");
    }

    public function translators() {
        return $this->belongsToMany(Member::class, "translators", "eventID", "memberID")
            ->withPivot("trID", "step", "currentChapter", "currentChunk", "isChecker");
    }

    public function translations() {
        return $this->hasMany(Translation::class, "eventID", "eventID");
    }

    public function comments() {
        return $this->hasMany(Comment::class, "eventID", "eventID");
    }

    public function checkersL2() {
        return $this->belongsToMany(Member::class, "checkers_l2", "eventID", "memberID")
            ->withPivot("step", "currentChapter", "peerCheck", "kwCheck", "crCheck");
    }

    public function checkersL3() {
        return $this->belongsToMany(Member::class, "checkers_l3", "eventID", "memberID")
            ->withPivot("step", "currentChapter", "peerCheck");
    }

    public function chapters() {
        return $this->hasMany(Chapter::class, "eventID", "eventID");
    }

    public function wordGroups() {
        return $this->hasMany(WordGroup::class, "eventID", "eventID");
    }

    public function words() {
        return $this->hasMany(Word::class, "eventID", "eventID");
    }

    public function translatorsWithChapters() {
        return $this->belongsToMany(Member::class, "chapters", "eventID", "memberID")
            ->withPivot("chapter", "chunks", "done", "checked", "l2checked", "l3checked");
    }

    public function chunks() {
        return $this->hasMany(Translation::class, "eventID", "eventID");
    }
}