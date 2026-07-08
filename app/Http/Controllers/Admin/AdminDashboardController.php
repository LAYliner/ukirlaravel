<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalBlogs' => Blog::count(),
            'totalUsers' => User::count(),
            'totalComments' => Comment::count(),
            'totalCategories' => Category::count(),
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