<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentContent extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function Content()
    {
        return $this->belongsTo(Content::class);
    }
}
