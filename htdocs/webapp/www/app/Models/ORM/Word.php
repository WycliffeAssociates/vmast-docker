<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Word extends Model
{
    protected $guarded = array("wordID", "eventID");
    protected $primaryKey = 'wordID';
    public $timestamps  = false;
}
