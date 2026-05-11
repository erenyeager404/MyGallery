<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeUnit\FunctionUnit;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'photo_id'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
