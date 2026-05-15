<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function dashboard()
    {
        $photos = Photo::where('status', 'public')
            ->with(['user', 'likes', 'saves', 'comments'])
            ->latest()
            ->get();
        // ↑ Ambil semua foto yang berstatus public
        // with() = eager loading — memuat relasi sekaligus dalam 1 query
        //   Tanpa with(): Laravel akan query ke DB untuk SETIAP foto (N+1 problem)
        //   Dengan with(): hanya 1 query tambahan per relasi
        // latest() = ORDER BY created_at DESC (foto terbaru duluan)

        return view('dashboard', compact('photos'));
        // ↑ compact('photos') = shortcut untuk ['photos' => $photos]
        // Variabel $photos bisa diakses di view dengan nama yang sama
    }

    public function showUpload()
    {
        return view('upload');
    }

    public function download(Photo $photo)
    {
        if ($photo->status == 'private' && $photo->user_id !== auth()->id()) {
            abort(403, 'Foto ini private.');
        }
        $path = Storage::disk('public')->path($photo->file_path);
        $filename = $photo->caption . '.' . pathinfo($path, PATHINFO_EXTENSION);
        return response()->download($path, $filename);
    }


    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            // ↑ image    → harus berupa file gambar
            // ↑ mimes    → hanya format ini yang diterima
            // ↑ max:5120 → maksimal 5MB (5120 KB)
            'caption' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:public,private',
            // ↑ in:public,private → nilainya harus salah satu dari ini
        ]);

        $path = $request->file('photo')->store('photos', 'public');
        // ↑ store('photos', 'public') menyimpan file ke storage/app/public/photos/
        // Laravel otomatis generate nama file unik (UUID)
        // $path akan berisi string seperti: "photos/AbCdEf123.jpg"

        Photo::create([
            'user_id' => auth()->id(),
            // ↑ auth()->id() = ID user yang sedang login
            'file_path' => $path,
            'caption' => $request->caption,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard')->with('success', 'Foto berhasil diupload!');
        // ↑ with('success', '...') menyimpan flash message ke session
        // Di view kita tampilkan dengan: session('success')
    }

    public function destroy(Photo $photo)
    {
        // ↑ Photo $photo = Route Model Binding
        // Laravel otomatis cari foto berdasarkan ID di URL, tidak perlu findOrFail()

        if ($photo->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
            // ↑ Hanya pemilik foto atau admin yang boleh menghapus
        }

        Storage::disk('public')->delete($photo->file_path);
        // ↑ Hapus file fisik dari storage terlebih dahulu
        // Jika tidak dihapus, file akan terus menumpuk di server

        $photo->delete();
        // ↑ Hapus record dari database

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}