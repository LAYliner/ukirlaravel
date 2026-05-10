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
        $query = Blog::with(['user', 'category']);

        // Filter by category_id
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if (in_array($status, ['draft', 'published'])) {
                $query->where('status', $status);
            }
        }

        // Search by title
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSortFields = ['id', 'title', 'created_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array(strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $blogs = $query->paginate(15)->withQueryString();

        // Get all categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('admin.blog.index', compact('blogs', 'categories'));
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
            'status' => ['required', 'in:draft,published'],
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
            'status' => ['required', 'in:draft,published'],
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