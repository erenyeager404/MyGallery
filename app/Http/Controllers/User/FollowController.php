<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Tidak bisa follow diri sendiri.'], 403);
        }
        $follow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $user->id)->first();
        if ($follow) {
            $follow->delete();
            $f = false;
        } else {
            Follow::create(['follower_id' => auth()->id(), 'following_id' => $user->id]);
            $f = true;
        }
        return response()->json(['is_following' => $f, 'total_followers' => $user->followers()->count()]);
    }
}