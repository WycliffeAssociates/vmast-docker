<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class WordGroup extends Model
{
    protected $table = "word_groups";
    protected $fillable = array();
    protected $primaryKey = 'groupID';
    public $timestamps  = false;
}