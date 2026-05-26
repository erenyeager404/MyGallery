<?php
namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Photo;

class LikeController extends Controller
{
    public function toggle(Photo $photo)
    {
        $like = Like::where('user_id', auth()->id())
            ->where('photo_id', $photo->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create(['user_id' => auth()->id(), 'photo_id' => $photo->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'total' => $photo->likes()->count(),
        ]);
    }
}