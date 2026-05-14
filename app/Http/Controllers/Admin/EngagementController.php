<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Photo;
use App\Models\Like;

class EngagementController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        // ↑ Total semua user terdaftar

        $onlineToday = User::whereDate('last_login_at', today())->count();
        // ↑ User yang login hari ini
        // whereDate() membandingkan hanya bagian tanggal (bukan jam/menit)
        // today() = tanggal hari ini tanpa waktu

        $totalPhotos = Photo::count();
        // ↑ Total semua foto yang diupload

        $topPhoto = Photo::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->first();
        // ↑ withCount('likes') menambah kolom 'likes_count' ke query
        // Ini lebih efisien daripada load semua likes lalu count di PHP
        // orderBy DESC + first() = ambil foto dengan like terbanyak

        return view('admin.engagement', compact(
            'totalUsers',
            'onlineToday',
            'totalPhotos',
            'topPhoto'
        ));
    }
}