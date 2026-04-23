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
            ->latest('created_at')
            ->paginate(9);

        return view('public.blog.index', compact('blogs'));
    }

    public function show(string $slug): View
    {
        // Fix: Relation 'user', Enum 'published', Eager load comments
        $blog = Blog::with(['user', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Fix: Column 'id', Relation 'user', Enum 'published'
        $relatedPosts = Blog::with('user')
            ->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->latest('created_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', compact('blog', 'relatedPosts'));
    }
}