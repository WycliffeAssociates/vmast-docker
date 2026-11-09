<?php


namespace App\Repositories\Admin;


interface IAdminRepository
{
    public function get($id);

    public function getByMember($memberID);

    public function getWith($relation);

    public function delete(&$self);

    public function save(&$self);
}