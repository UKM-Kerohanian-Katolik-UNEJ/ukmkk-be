<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Content extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ["id"];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function ContentViews()
    {
        return $this->hasMany(ContentView::class);
    }

    public function Comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ContentSchedules()
    {
        return $this->hasMany(ContentSchedule::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("gambar_andalan_konten");
        $this->addMediaCollection("galeri_konten");
    }

    public function getRouteKeyName()
    {
        return "slug";
    }
}
