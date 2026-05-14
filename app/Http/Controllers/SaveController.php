<?php

namespace App\Http\Controllers;

use App\Models\Save;
use App\Models\Photo;

class SaveController extends Controller
{
    public function toggle(Photo $photo)
    {
        $userId = auth()->id();

        $save = Save::where('user_id', $userId)
            ->where('photo_id', $photo->id)
            ->first();

        if ($save) {
            $save->delete();
            $saved = false;
        } else {
            Save::create([
                'user_id' => $userId,
                'photo_id' => $photo->id,
            ]);
            $saved = true;
        }

        return response()->json(['saved' => $saved]);
    }

    public function index()
    {
        $photos = auth()->user()
            ->saves()
            ->with('photo.user', 'photo.likes', 'photo.saves', 'photo.comments')
            ->latest()
            ->get()
            ->pluck('photo');
        // ↑ Ambil semua save milik user yang login
        // with('photo.user') = eager load relasi berantai: save → photo → user
        // pluck('photo') = ambil hanya objek photo-nya saja dari koleksi save

        return view('saved', compact('photos'));
    }
}