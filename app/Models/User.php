<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $cast = [
        'is_admin' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
        ;
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}