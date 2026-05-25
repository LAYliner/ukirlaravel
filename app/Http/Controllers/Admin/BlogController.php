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
            'is_visible' => $request->status === 'published',
            'published_at' => $request->status === 'published' ? now() : null,
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

        $is_visible = $blog->is_visible;
        $published_at = $blog->published_at;

        if ($blog->status !== $request->status) {
            $is_visible = $request->status === 'published';
            if ($request->status === 'published') {
                $published_at = now();
            }
        }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'status' => $request->status,
            'is_visible' => $is_visible,
            'published_at' => $published_at,
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

    public function toggleVisibility(string $id): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Akses ditolak.');

        $blog = Blog::findOrFail($id);

        // Blokir toggle pada status draft
        if ($blog->status !== 'published') {
            return redirect()->back()->with('error', 'Visibilitas hanya dapat diubah pada blog yang sudah Published.');
        }

        $blog->update([
            'is_visible' => !$blog->is_visible,
            'updated_at' => now(),
        ]);

        $action = $blog->is_visible ? 'dipublikasikan' : 'diunpublish';
        return redirect()->back()->with('success', "Blog berhasil {$action}.");
    }

    public function updateStatus(string $id, Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Akses ditolak.');

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:draft,published'],
        ]);

        $blog = Blog::findOrFail($id);

        if ($blog->status === $validated['status']) return redirect()->back();

        // Auto-couple visibility dengan status
        if ($validated['status'] === 'published') {
            $blog->update([
                'status' => 'published',
                'is_visible' => true,
                'published_at' => now(),
            ]);
        } else {
            $blog->update([
                'status' => 'draft',
                'is_visible' => false,
            ]);
        }

        return redirect()->back()->with('success', "Status blog berhasil diubah ke {$validated['status']}.");
    }
}