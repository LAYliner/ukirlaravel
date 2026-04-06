<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $blogs = Blog::with('user')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.blog.index', compact('blogs'));
    }

    public function create(): View
    {
        $categories = Category::all(); 
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'thumbnail_path' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,rejected'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        // Fix: Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists, add counter if needed
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data = [
            'id' => Str::uuid()->toString(),
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'status' => $request->status,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'thumbnail_path' => null,
        ];

        if ($request->hasFile('thumbnail_path')) {
            $data['thumbnail_path'] = $request->file('thumbnail_path')->store('thumbnails', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog berhasil dibuat.');
    }

    public function edit(string $id)//: View
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'thumbnail_path' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,rejected'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        // Fix: Generate unique slug (exclude current blog)
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('thumbnail_path')) {
            $data['thumbnail_path'] = $request->file('thumbnail_path')->store('thumbnails', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog berhasil dihapus.');
    }
}