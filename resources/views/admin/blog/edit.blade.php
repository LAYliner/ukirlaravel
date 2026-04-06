@extends('layouts.admin')
@section('title', 'Edit Blog')
@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 style="margin-bottom: 1.5rem;">📝 Edit Blog</h1>

    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 1rem;">
            <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Judul</label>
            <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" required
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            @error('title')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="content" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Isi Konten</label>
            <textarea name="content" id="content" rows="10" required
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">{{ old('content', $blog->content) }}</textarea>
            @error('content')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="thumbnail_path" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Thumbnail (Opsional)</label>
            @if($blog->thumbnail_path)
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ asset('storage/' . $blog->thumbnail_path) }}" alt="Thumbnail" style="max-width: 200px; border-radius: 4px;">
                </div>
            @endif
            <input type="file" name="thumbnail_path" id="thumbnail_path" accept="image/*"
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            <small style="color: #666;">Kosongkan jika tidak ingin mengganti thumbnail.</small>
            @error('thumbnail_path')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="category_id" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Kategori (Opsional)</label>
            <select name="category_id" id="category_id"
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="">Pilih Kategori</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Status</label>
            <select name="status" id="status" required
                style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                <option value="rejected" {{ old('status', $blog->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="padding: 0.5rem 1.5rem; background: var(--accent-color); color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                Perbarui
            </button>
            <a href="{{ route('admin.blog.index') }}" style="padding: 0.5rem 1.5rem; background: #fff; color: var(--text-dark); border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection