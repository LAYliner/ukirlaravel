<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Show public blog list (only published).
     */
    public function index(): View
    {
        $blogs = Blog::with('admin')
            ->where('status_blog', 'publish')
            ->latest('created_at')
            ->paginate(9);

        return view('public.blog.index', compact('blogs'));
    }

    /**
     * Show single blog post by slug.
     */
    public function show(string $slug): View
    {
        $blog = Blog::with('admin')
            ->where('slug', $slug)
            ->where('status_blog', 'publish')
            ->firstOrFail();

        // Get related posts (same category or recent)
        $relatedPosts = Blog::with('admin')
            ->where('status_blog', 'publish')
            ->where('id_blog', '!=', $blog->id_blog)
            ->latest('created_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', compact('blog', 'relatedPosts'));
    }
}