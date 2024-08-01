<?php


namespace App\Repositories\Member;


use App\Models\ORM\Member;
use App\Models\ORM\Profile;

class MemberRepository implements IMemberRepository
{
    protected $member = null;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function create($data, $profileData)
    {
        $member = new Member($data);
        $member->save();

        $profile = new Profile($profileData);
        $profile->mID = $member->memberID;
        $profile->save();

        return $member;
    }

    public function get($id)
    {
        return $this->member::find($id);
    }

    public function getByUsername($userName)
    {
        return $this->member::where("userName", $userName)->first();
    }

    public function getByEmail($email) {
        return $this->member::where("email", $email)->first();
    }

    public function getByEmailOrUserName($emailOrUserName) {
        return $this->member::where("email", $emailOrUserName)
            ->orWhere("userName", $emailOrUserName)
            ->first();
    }

    public function getWith($id, $relation)
    {
        return $this->member::with($relation)->find($id);
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
        return call_user_func_array([$this->member, $method], array_values($args));
    }
}