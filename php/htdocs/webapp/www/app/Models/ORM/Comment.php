<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Comment extends Model
{
    protected $guarded = array("cID", "eventID", "memberID");
    protected $primaryKey = 'cID';
    public $timestamps  = false;

    public function commentor() {
        return $this->belongsTo(Member::class, "memberID", "memberID");
    }
}