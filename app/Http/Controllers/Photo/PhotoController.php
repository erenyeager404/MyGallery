<?php
namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Photo;
use App\Models\PhotoFile;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        // Admin tidak boleh di user dashboard
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $photos = Photo::where('status', 'public')
            ->with([
                'user',
                'files',
                'likes',
                'saves',
                'comments',
                'tags',
                'eventParticipation.event'
            ])
            ->latest()->paginate(16);

        return view('dashboard.index', compact('photos'));
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

        $photo->load([
            'user',
            'files',
            'likes',
            'saves',
            'comments.user',
            'tags',
            'eventParticipation.event'
        ]);

        return view('photos.show', compact('photo'));
    }

    public function showUpload()
    {
        $activeEvents = Event::whereIn('status', ['active'])
            ->where('end_date', '>', now())
            ->orderBy('end_date')
            ->get();
        return view('photos.upload', compact('activeEvents'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:20480',
            'caption' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:public,private',
            'tags' => 'nullable|string|max:255',
            'event_id' => 'nullable|exists:events,id',
        ]);

        $event = null;
        if ($request->filled('event_id')) {
            $event = Event::find($request->event_id);

            // Jika event tidak ditemukan atau sudah berakhir, skip saja — jangan block upload
            if (!$event || $event->status === 'ended') {
                $event = null;
            }

            // Jika user sudah ikut event ini, skip event tapi tetap upload foto
            if ($event && $event->hasUserJoined(auth()->id())) {
                $event = null;
                session()->flash('warning', 'Kamu sudah ikut event ini. Foto tetap diupload tanpa event.');
            }
        }

        $files = $request->file('photos');
        $albumId = count($files) > 1 ? Str::uuid()->toString() : null;

        $tagInput = $request->tags ?? '';
        if ($event?->auto_tag) {
            $tagInput = trim($tagInput . ',' . $event->auto_tag . ',EventMemora', ',');
        }

        $photo = Photo::create([
            'user_id' => auth()->id(),
            'album_id' => $albumId,
            'caption' => $request->caption,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $manager = new ImageManager(new Driver());

        foreach ($files as $i => $file) {
            $uuid = Str::uuid()->toString();

            try {
                $origImg = $manager->read($file->getPathname())
                    ->scaleDown(width: 1920, height: 1920)
                    ->toWebp(88);
                $origPath = "photos/orig/{$uuid}.webp";
                Storage::disk('public')->put($origPath, $origImg);

                $thumbImg = $manager->read($file->getPathname())
                    ->scaleDown(width: 800, height: 800)
                    ->toWebp(72);
                $thumbPath = "photos/thumb/{$uuid}.webp";
                Storage::disk('public')->put($thumbPath, $thumbImg);
            } catch (\Exception $e) {
                // Fallback: simpan file asli tanpa kompresi
                $origPath = $file->store('photos/orig', 'public');
                $thumbPath = null;
            }

            PhotoFile::create([
                'photo_id' => $photo->id,
                'file_path' => $origPath,
                'thumb_path' => $thumbPath ?? $origPath,
                'order' => $i,
            ]);
        }

        if ($tagInput) {
            $tagIds = collect(
                array_unique(array_filter(array_map('trim', explode(',', strtolower($tagInput)))))
            )->map(fn($n) => Tag::firstOrCreate(['name' => $n])->id);
            $photo->tags()->sync($tagIds);
        }

        if ($event) {
            try {
                EventParticipation::create([
                    'event_id' => $event->id,
                    'photo_id' => $photo->id,
                    'user_id' => auth()->id(),
                ]);
            } catch (\Exception $e) {

            }
        }

        $redirectRoute = auth()->user()->is_admin ? 'admin.dashboard' : 'dashboard';

        return redirect()->route($redirectRoute)
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
        $ext = 'webp';
        return response()->download($path, Str::slug($photo->caption) . '.' . $ext);
    }

    public function destroy(Photo $photo)
    {
        if ($photo->user_id !== auth()->id() && !auth()->user()->is_admin)
            abort(403);
        foreach ($photo->files as $f) {
            Storage::disk('public')->delete($f->file_path);
            if ($f->thumb_path)
                Storage::disk('public')->delete($f->thumb_path);
        }
        $photo->delete();
        return redirect()->route('dashboard')->with('success', 'Foto berhasil dihapus.');
    }
}