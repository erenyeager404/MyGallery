<?php
namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'photo_id' => $photo->id,
            'body' => $request->body,
        ]);
        $comment->load('user');

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->diffForHumans(),
            ],
            'total' => $photo->comments()->count(),
        ]);
    }
}