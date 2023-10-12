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

    public function CommentContents()
    {
        return $this->hasMany(CommentContent::class);
    }

    public function ContentViews()
    {
        return $this->hasMany(ContentViews::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("gambar-andalan-konten");
        $this->addMediaCollection("galeri-konten");
    }
}
