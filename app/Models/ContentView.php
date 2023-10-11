<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentView extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Content()
    {
        return $this->belongsTo(Content::class);
    }
}
