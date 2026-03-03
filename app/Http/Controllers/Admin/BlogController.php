<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $blogs = Blog::with('admin')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.blog.index', compact('blogs'));
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'status_blog' => ['required', 'in:draft,publish'],
        ]);

        $data = [
            'id_blog' => Str::uuid()->toString(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'slug' => Str::slug($request->judul),
            'status_blog' => $request->status_blog,
            'id_admin' => auth()->id(),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $data['thumbnail'] = '';
        }

        Blog::create($data);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog berhasil dibuat.');
    }

    public function edit(string $id): View
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'status_blog' => ['required', 'in:draft,publish'],
        ]);

        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'slug' => Str::slug($request->judul),
            'status_blog' => $request->status_blog,
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
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