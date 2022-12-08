<?php
namespace App\Models;

use Database\Model;
use Helpers\Data;

class SailDictionaryModel extends Model
{
    public  function __construct()
    {
        parent::__construct();
    }

    public function getSunDictionary()
    {
        return $this->db->table("sail_dict")
            ->orderBy("word")->get();
    }


    public function getSunWord($where)
    {
        return $this->db->table("sail_dict")
            ->where($where)
            ->get();
    }

    public function createSunWord($data)
    {
        return $this->db->table("sail_dict")
            ->insertGetId($data);
    }


    public function deleteSunWord($where)
    {
        return $this->db->table("sail_dict")
            ->where($where)
            ->delete();
    }


    public function deleteAllWords() {
        return $this->db->table("sail_dict")
            ->delete();
    }
}