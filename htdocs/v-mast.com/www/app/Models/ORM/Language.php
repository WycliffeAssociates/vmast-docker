<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Language extends Model
{
    protected $primaryKey = 'langID';
    protected $guarded = array();
    public $timestamps  = false;
}