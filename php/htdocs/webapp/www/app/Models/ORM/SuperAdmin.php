<?php
namespace App\Models\ORM;

use Database\ORM\Model;

class SuperAdmin extends Model
{
    protected $table = "super_admins";
    protected $fillable = array("memberID");
    protected $primaryKey = 'saID';
    public $timestamps  = false;

    public function member()
    {
        return $this->hasOne(Member::class, "memberID", "memberID");
    }
}
