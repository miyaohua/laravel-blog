<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Commen extends Model
{
    use HasFactory;

    public function parentComment()
    {
        return $this->belongsTo(Commen::class, 'parent_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
