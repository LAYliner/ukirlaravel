<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        // Fix: Relation 'user', Enum 'published'
        $blogs = Blog::with('user')
            ->where('status', 'published')
            ->where('is_visible', true)
            ->latest('created_at')
            ->paginate(9);

        return view('public.blog.index', compact('blogs'));
    }

    public function show(string $slug): View
    {
        // Fix: Relation 'user', Enum 'published', Eager load comments (including soft deleted for proper display)
        $blog = Blog::with(['user', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest()->withTrashed();
            }])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('is_visible', true)
            ->firstOrFail();

        // Fix: Column 'id', Relation 'user', Enum 'published'
        $relatedPosts = Blog::with('user')
            ->where('status', 'published')
            ->where('is_visible', true)
            ->where('id', '!=', $blog->id)
            ->latest('created_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', compact('blog', 'relatedPosts'));
    }
}