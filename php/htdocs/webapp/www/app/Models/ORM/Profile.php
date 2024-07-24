<?php


namespace App\Models\ORM;

use \Database\ORM\Model;

class Profile extends Model
{
    protected $table = "profile";
    protected $primaryKey = 'pID';
    protected $guarded = array("pID", "mID");
    public $timestamps  = false;

    public function member()
    {
        return $this->belongsTo(Member::class, "memberID", "mID");
    }
}