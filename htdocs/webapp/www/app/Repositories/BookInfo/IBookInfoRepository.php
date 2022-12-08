<?php


namespace App\Repositories\BookInfo;


interface IBookInfoRepository
{
    public function get($id);

    public function getWith($relation);

    public function getByBookCode($code);

    public function delete(&$self);

    public function save(&$self);
}