<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class CheckerL3 extends Model
{
    protected $table = "checkers_l3";
    protected $guarded = array("l3chID", "memberID", "eventID");
    protected $primaryKey = 'l3chID';
    public $timestamps  = false;

    public function event() {
        return $this->belongsTo(Event::class, "eventID", "eventID");
    }
    
    public function chapters()
    {
        return $this->hasMany(Chapter::class, "l3chID", "l3chID");
    }

    public function translations() {
        return $this->hasMany(Translation::class, "l3chID");
    }

    public function checkers() {
        return $this->hasMany(
            Checker::class,
            "l3chID"
        );
    }
}