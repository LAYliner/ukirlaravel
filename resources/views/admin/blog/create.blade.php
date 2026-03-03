@extends('layouts.admin')

@section('title', 'Buat Blog Baru')

@section('content')
    <div class="card">
        <h2>📝 Buat Blog Baru</h2>
        <br>
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="judul" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Judul</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                @error('judul')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="isi" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Isi Konten</label>
                <textarea name="isi" id="isi" rows="10" required
                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">{{ old('isi') }}</textarea>
                @error('isi')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="thumbnail" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Thumbnail (Opsional)</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                @error('thumbnail')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="status_blog" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Status</label>
                <select name="status_blog" id="status_blog" required
                    style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                    <option value="draft" {{ old('status_blog') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="publish" {{ old('status_blog') === 'publish' ? 'selected' : '' }}>Publish</option>
                </select>
                @error('status_blog')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="padding: 0.5rem 1.5rem; background: var(--accent-color); color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                    Simpan
                </button>
                <a href="{{ route('admin.blog.index') }}" style="padding: 0.5rem 1.5rem; background: #fff; color: var(--text-dark); border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection