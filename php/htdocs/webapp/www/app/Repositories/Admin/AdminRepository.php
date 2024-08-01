<?php


namespace App\Repositories\Admin;


use App\Models\ORM\SuperAdmin;

class AdminRepository implements IAdminRepository
{
    protected $superAdmin = null;

    public function __construct(SuperAdmin $superAdmin)
    {
        $this->superAdmin = $superAdmin;
    }

    public function get($id)
    {
        return $this->superAdmin::find($id);
    }

    public function getByMember($memberID)
    {
        return $this->superAdmin::where("memberID", $memberID)->first();
    }

    public function getWith($relation)
    {
        return $this->superAdmin::with($relation)->get();
    }

    public function delete(&$self)
    {
        return $self->delete();
    }

    public function save(&$self)
    {
        return $self->save();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->superAdmin, $method], array_values($args));
    }
}