<?php
namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\PhotoFile;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function landing()
    {
        $photos = Photo::where('status', 'public')
            ->with(['user', 'files', 'likes', 'saves', 'comments', 'tags'])
            ->latest()->paginate(12);
        return view('landing.index', compact('photos'));
    }

    public function dashboard()
    {
        $photos = Photo::where('status', 'public')
            ->with(['user', 'files', 'likes', 'saves', 'comments', 'tags'])
            ->latest()->paginate(12);

        $bgPhoto = Photo::where('status', 'public')
            ->with('files')->inRandomOrder()->first();

        return view('dashboard.index', compact('photos', 'bgPhoto'));
    }

    public function show(Photo $photo, Request $request)
    {
        if ($photo->status === 'private') {
            if (!auth()->check() || auth()->id() !== $photo->user_id)
                abort(403);
        }

        $key = 'viewed_photo_' . $photo->id;
        if (!session()->has($key)) {
            $photo->increment('views');
            session()->put($key, true);
        }

        $photo->load(['user', 'files', 'likes', 'saves', 'comments.user', 'tags']);

        // Partial = dipanggil dari modal
        if ($request->boolean('partial')) {
            return view('photos.show', compact('photo'));
        }

        return view('photos.show', compact('photo'));
    }

    public function showUpload()
    {
        return view('photos.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'caption' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:public,private',
            'tags' => 'nullable|string|max:255',
        ]);

        $files = $request->file('photos');
        $albumId = count($files) > 1 ? Str::uuid()->toString() : null;

        $photo = Photo::create([
            'user_id' => auth()->id(),
            'album_id' => $albumId,
            'caption' => $request->caption,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        foreach ($files as $i => $file) {
            PhotoFile::create([
                'photo_id' => $photo->id,
                'file_path' => $file->store('photos', 'public'),
                'order' => $i,
            ]);
        }

        if ($request->filled('tags')) {
            $tagIds = collect(
                array_filter(array_map('trim', explode(',', strtolower($request->tags))))
            )->map(fn($n) => Tag::firstOrCreate(['name' => $n])->id);
            $photo->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Foto berhasil diupload!');
    }

    public function download(Photo $photo)
    {
        if ($photo->status === 'private') {
            if (!auth()->check() || auth()->id() !== $photo->user_id)
                abort(403);
        }
        $file = $photo->files()->first();
        if (!$file)
            abort(404);
        $path = Storage::disk('public')->path($file->file_path);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return response()->download($path, Str::slug($photo->caption) . '.' . $ext);
    }

    public function destroy(Photo $photo)
    {
        if ($photo->user_id !== auth()->id() && !auth()->user()->is_admin)
            abort(403);
        foreach ($photo->files as $f) {
            Storage::disk('public')->delete($f->file_path);
        }
        $photo->delete();
        return back()->with('success', 'Foto berhasil dihapus.');
    }
}