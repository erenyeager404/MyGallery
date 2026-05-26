<?php
namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Save;
use App\Models\Photo;

class SaveController extends Controller
{
    public function toggle(Photo $photo)
    {
        $save = Save::where('user_id', auth()->id())
            ->where('photo_id', $photo->id)->first();
        if ($save) {
            $save->delete();
            $saved = false;
        } else {
            Save::create(['user_id' => auth()->id(), 'photo_id' => $photo->id]);
            $saved = true;
        }
        return response()->json(['saved' => $saved]);
    }

    public function index()
    {
        $photos = auth()->user()->saves()
            ->with([
                'photo.files',
                'photo.user',
                'photo.likes',
                'photo.saves',
                'photo.comments',
                'photo.tags'
            ])
            ->latest()->get()->pluck('photo')->filter();
        return view('saved.index', compact('photos'));
    }
}