<?php


namespace app\Models\ORM;

use Database\ORM\Model;

class Keyword extends Model
{
    protected $guarded = array("kID", "eventID", "memberID");
    protected $primaryKey = 'kID';
    public $timestamps  = false;

    public function creator() {
        return $this->belongsTo(Member::class, "memberID", "memberID");
    }
}