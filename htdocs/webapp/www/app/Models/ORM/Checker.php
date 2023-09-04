<?php

namespace app\Models\ORM;

use Database\ORM\Model;

class Checker extends Model
{
    protected $guarded = array("trID", "memberID");
    protected $primaryKey = 'trID';
    public $timestamps  = false;

    public function member() {
        return $this->belongsTo(Member::class, "memberID", "memberID");
    }

    public function translator() {
        return $this->belongsTo(Translator::class, "trID", "trID");
    }
}