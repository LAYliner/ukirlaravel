<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch latest 3 published projects
        $latestProjects = Project::with('user')
            ->where('status', 'published')
            ->where('is_visible', true)
            ->latest()
            ->take(3)
            ->get();

        // Fetch latest 3 published blogs
        $latestBlogs = Blog::with('user')
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('latestProjects', 'latestBlogs'));
    }
}
