<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;

class DashboardController extends Controller
{
    public function index()
    {
        $photos = Photo::where('status', 'public')
            ->with(['user', 'files', 'likes', 'saves', 'comments'])
            ->latest()->paginate(20);
        return view('admin.dashboard', compact('photos'));
    }
}