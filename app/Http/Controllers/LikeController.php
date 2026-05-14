<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Photo $photo)
    {
        $userId = auth()->id();

        $like = Like::where('user_id', $userId)
            ->where('photo_id', $photo->id)
            ->first();
        // ↑ Cari apakah user ini sudah pernah like foto ini

        if ($like) {
            $like->delete();
            // ↑ Jika sudah like → hapus (unlike)
            $liked = false;
        } else {
            Like::create([
                'user_id' => $userId,
                'photo_id' => $photo->id,
            ]);
            // ↑ Jika belum like → buat record baru (like)
            $liked = true;
        }

        $totalLikes = $photo->likes()->count();
        // ↑ Hitung total like setelah toggle

        return response()->json([
            'liked' => $liked,
            'total' => $totalLikes,
        ]);
        // ↑ Return JSON karena tombol like akan pakai fetch() JavaScript
        // Jadi halaman tidak perlu reload saat like/unlike
    }
}