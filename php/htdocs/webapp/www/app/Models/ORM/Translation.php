<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Translation extends Model
{
    protected $guarded = array("tID", "eventID", "trID", "l2chID", "l3chID");
    protected $primaryKey = 'tID';
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
}