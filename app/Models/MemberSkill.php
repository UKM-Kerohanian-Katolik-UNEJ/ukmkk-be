<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberSkill extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Member()
    {
        return $this->belongsTo(Member::class);
    }
}
