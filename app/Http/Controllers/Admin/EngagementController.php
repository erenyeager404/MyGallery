<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\User;

class EngagementController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $onlineToday = User::whereDate('last_login_at', today())->count();
        $totalPhotos = Photo::count();
        $topPhoto = Photo::withCount('likes')
            ->with(['files', 'user'])
            ->orderBy('likes_count', 'desc')->first();

        return view('admin.engagement', compact(
            'totalUsers',
            'onlineToday',
            'totalPhotos',
            'topPhoto'
        ));
    }
}