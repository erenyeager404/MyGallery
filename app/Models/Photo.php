<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'album_id',
        'caption',
        'description',
        'status',
        'views',
    ];

    // ── Relasi ──────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(PhotoFile::class)->orderBy('order');
        // urut sesuai kolom order
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
        // komentar terbaru duluan
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'photo_tag');
    }

    // ── Helpers ─────────────────────────────────
    // Ambil URL foto pertama (thumbnail)
    public function getThumbnailAttribute(): string
    {
        $first = $this->files()->first();
        return $first ? asset('storage/' . $first->file_path) : '';
    }

    public function isLikedBy(?int $userId): bool
    {
        if (!$userId)
            return false;
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isSavedBy(?int $userId): bool
    {
        if (!$userId)
            return false;
        return $this->saves()->where('user_id', $userId)->exists();
    }
}