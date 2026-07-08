<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalBlogs' => Blog::published()->count(),
            'totalProjects' => Project::published()->count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalComments' => Comment::count(),
            'mostViewedBlogs' => Blog::orderBy('views', 'desc')->take(5)->get(),
            'mostViewedProjects' => Project::orderBy('views', 'desc')->take(5)->get(),
            'recentComments' => Comment::with(['user', 'commentable'])->orderBy('created_at', 'desc')->take(10)->get(),
        ];

        return view('admin.dashboard', $data);
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->storeAs('uploads/editor', $fileName, 'public');

            $url = asset('storage/uploads/editor/' . $fileName);
            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json([
            'error' => [
                'message' => 'No file uploaded.'
            ]
        ], 400);
    }
}