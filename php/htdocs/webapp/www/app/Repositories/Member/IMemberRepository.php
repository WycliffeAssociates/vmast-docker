<?php


namespace App\Repositories\Member;


interface IMemberRepository
{
    public function create($data, $profileData);

    public function get($id);

    public function getByUsername($userName);

    public function getByEmail($email);

    public function getByEmailOrUserName($emailOrUserName);

    public function getWith($id, $relation);

    public function delete(&$self);

    public function save(&$self);
}