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
}