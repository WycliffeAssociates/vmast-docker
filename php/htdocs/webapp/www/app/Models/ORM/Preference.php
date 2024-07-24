<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Preference extends Model
{
    protected $fillable = array("prefKey", "prefValue");
    protected $primaryKey = 'prefID';
    public $timestamps  = false;
}