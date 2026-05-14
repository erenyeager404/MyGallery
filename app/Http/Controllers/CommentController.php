<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'photo_id' => $photo->id,
            'body' => $request->body,
        ]);

        $comment->load('user');
        // ↑ Load relasi user setelah comment dibuat
        // Kita butuh nama user untuk ditampilkan di response JSON

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->diffForHumans(),
                // ↑ diffForHumans() = "2 menit yang lalu", "1 jam yang lalu" dll
            ]
        ]);
    }
}