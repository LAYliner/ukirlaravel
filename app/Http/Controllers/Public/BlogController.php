<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        // Sanitasi input search
        $search = $request->input('search');
        $categoryId = $request->input('category');

        // Query blog dengan scope published, search, dan filter kategori
        $blogs = Blog::published()
            ->with(['user', 'category'])
            ->search($search)
            ->filterByCategory($categoryId)
            ->latest('created_at')
            ->paginate(9)
            ->appends($request->only('search', 'category'));

        // Ambil semua kategori untuk dropdown filter (hanya yang memiliki blog published)
        $categories = Category::whereHas('blogs', function ($query) {
                $query->published();
            })
            ->orderBy('name')
            ->get();

        return view('public.blog.index', compact('blogs', 'categories'));
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

        // Increment view count
        $blog->incrementViews();

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