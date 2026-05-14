<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;

class DashboardController extends Controller
{
    public function index()
    {
        $photos = Photo::where('status', 'public')
            ->with(['user', 'likes', 'saves', 'comments'])
            ->latest()
            ->get();
        // ↑ Sama seperti dashboard user, tapi di view admin ada tombol hapus

        return view('admin.dashboard', compact('photos'));
    }
}