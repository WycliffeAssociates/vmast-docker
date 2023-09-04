<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class CheckerL2 extends Model
{
    protected $table = "checkers_l2";
    protected $guarded = array("l2chID", "memberID", "eventID");
    protected $primaryKey = 'l2chID';
    public $timestamps  = false;

    public function event() {
        return $this->belongsTo(Event::class, "eventID", "eventID");
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, "l2chID", "l2chID");
    }

    public function translations() {
        return $this->hasMany(Translation::class, "l2chID");
    }

    public function checkers() {
        return $this->hasMany(
            Checker::class,
            "l2chID"
        );
    }
}