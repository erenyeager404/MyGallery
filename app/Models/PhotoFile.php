<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoFile extends Model
{
    protected $fillable = ['photo_id', 'file_path', 'order'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    // Helper: URL lengkap file
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}