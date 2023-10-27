<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable implements HasMedia, JWTSubject
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ["id"];

    protected $hidden = [
        'password'
    ];

    public function MemberSkills()
    {
        return $this->hasMany(MemberSkill::class);
    }

    public function Comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("avatar");
        $this->addMediaCollection("ktm");
    }

    public function getRouteKeyName()
    {
        return "slug";
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // method untuk login
    
}
