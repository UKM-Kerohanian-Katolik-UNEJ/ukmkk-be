<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSchedule extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Content()
    {
        return $this->belongsTo(Content::class);
    }
}
