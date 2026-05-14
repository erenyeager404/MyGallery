<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $photos = $user->photos()->latest()->get();
        // ↑ Ambil SEMUA foto milik user (public + private)
        // Karena ini halaman profile sendiri, private pun boleh tampil

        return view('profile', compact('user', 'photos'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
            // ↑ Hash::check() membandingkan plain text dengan hash di database
            // Jika tidak cocok, kembalikan ke form dengan pesan error
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
            // ↑ Enkripsi password baru sebelum disimpan
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}