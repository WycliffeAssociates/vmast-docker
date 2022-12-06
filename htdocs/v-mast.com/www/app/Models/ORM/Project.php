<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class Project extends Model
{
    protected $guarded = array("projectID", "glID");
    protected $primaryKey = 'projectID';
    public $timestamps  = false;

    public function admins()
    {
        return $this->belongsToMany(
            Member::class,
            "member_project",
            "projectID",
            "memberID"
        );
    }

    public function gatewayLanguage() {
        return $this->belongsTo(GatewayLanguage::class, "glID");
    }

    public function targetLanguage() {
        return $this->belongsTo(Language::class, "targetLang", "langID");
    }

    public function events() {
        return $this->hasMany(Event::class, "projectID");
    }
}