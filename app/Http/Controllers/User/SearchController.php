<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $photos = collect();
        $tags = collect();

        if ($query) {
            $photos = Photo::where('status', 'public')
                ->where(function ($q) use ($query) {
                    $q->where('caption', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('tags', fn($q) => $q->where('name', 'like', "%{$query}%"))
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$query}%"));
                })
                ->with(['user', 'files', 'likes', 'saves', 'comments', 'tags'])
                ->latest()->get();

            $tags = Tag::where('name', 'like', "%{$query}%")
                ->withCount('photos')
                ->orderBy('photos_count', 'desc')
                ->take(10)->get();
        }

        $popularTags = Tag::withCount('photos')
            ->orderBy('photos_count', 'desc')
            ->take(20)->get();

        return view('search.index', compact('photos', 'tags', 'query', 'popularTags'));
    }
}