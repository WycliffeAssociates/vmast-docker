<?php


namespace App\Models\ORM;

use Database\ORM\Model;

class GatewayLanguage extends Model
{
    protected $table = "gateway_languages";
    protected $fillable = array("gwLang");
    protected $primaryKey = 'glID';
    public $timestamps  = false;

    public function admins()
    {
        return $this->belongsToMany(
            Member::class,
            "member_gl",
            "glID",
            "memberID"
        );
    }

    public function language() {
        return $this->belongsTo(Language::class, "gwLang", "langID");
    }

    public function projects() {
        return $this->hasMany(Project::class, "glID");
    }
}