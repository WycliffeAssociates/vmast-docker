<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Member extends Model
{
    protected $primaryKey = "memberID";
    protected $hidden = array("password");
    protected $guarded = array("memberID");
    public $timestamps  = false;

    public function profile()
    {
        return $this->hasOne(Profile::class, "mID", "memberID");
    }

    public function superAdmin()
    {
        return $this->hasOne(SuperAdmin::class, "memberID");
    }

    public function adminGatewayLanguages()
    {
        return $this->belongsToMany(
            GatewayLanguage::class,
            "member_gl",
            "memberID",
            "glID"
        );
    }

    public function adminProjects()
    {
        return $this->belongsToMany(
            Project::class,
            "member_project",
            "memberID",
            "projectID"
        );
    }

    public function adminEvents()
    {
        return $this->belongsToMany(
            Event::class,
            "member_event",
            "memberID",
            "eventID"
        );
    }

    public function translators()
    {
        return $this->hasMany(
            Translator::class,
            "memberID",
            "memberID"
        );
    }

    public function checkersL2()
    {
        return $this->hasMany(
            CheckerL2::class,
            "memberID",
            "memberID"
        );
    }

    public function checkersL3()
    {
        return $this->hasMany(
            CheckerL3::class,
            "memberID",
            "memberID"
        );
    }

    public function translatorCheckers()
    {
        return $this->belongsToMany(
            Translator::class,
            "checkers",
            "memberID",
            "trID"
        );
    }

    public function chapters() {
        return $this->hasMany(Chapter::class, "memberID", "memberID");
    }

    public function chaptersL2() {
        return $this->hasMany(Chapter::class, "l2memberID", "memberID");
    }

    public function chaptersL3() {
        return $this->hasMany(Chapter::class, "l3memberID", "memberID");
    }

    public function isSuperAdmin()
    {
        return $this->superAdmin()->exists();
    }

    public function isGlAdmin()
    {
        return $this->adminGatewayLanguages()->count() > 0;
    }

    public function isProjectAdmin()
    {
        return $this->adminProjects()->count() > 0;
    }

    public function isBookAdmin()
    {
        return $this->adminEvents()->count() > 0;
    }
}