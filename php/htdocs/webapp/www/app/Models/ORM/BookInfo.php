<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class BookInfo extends Model
{
    protected $table = "book_info";
    protected $fillable = array();
    protected $primaryKey = 'sort';
    public $timestamps  = false;
}