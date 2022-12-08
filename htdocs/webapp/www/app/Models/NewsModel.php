<?php
namespace App\Models;

use Database\Model;
use Helpers\Data;

class NewsModel extends Model
{
    public  function __construct()
    {
        parent::__construct();
    }

    public function getNews()
    {
        return $this->db->table("news")
            ->where("type", "news")
            ->orderBy("id", "desc")
            ->get();
    }

    public function getFaqs()
    {
        return $this->db->table("news")
            ->where("type", "faq")
            ->orderBy("id", "desc")
            ->get();
    }

    public function createNews($data)
    {
        $data["type"] = "news";
        return $this->db->table("news")
            ->insertGetId($data);
    }

    public function createFaqs($data)
    {
        $data["type"] = "faq";
        return $this->db->table("news")
            ->insertGetId($data);
    }

    public function deleteNews($where)
    {
        $where["type"] = "news";
        return $this->db->table("news")
            ->where($where)
            ->delete();
    }

    public function deleteFaqs($where)
    {
        $where["type"] = "faq";
        return $this->db->table("news")
            ->where($where)
            ->delete();
    }

    public function updateNews($data, $where)
    {
        $where["type"] = "news";
        return $this->db->table("news")
            ->where($where)
            ->update($data);
    }

    public function updateFaqs($data, $where)
    {
        $where["type"] = "faq";
        return $this->db->table("news")
            ->where($where)
            ->update($data);
    }
}