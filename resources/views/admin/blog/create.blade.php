@extends('layouts.admin')

@section('title', 'Buat Blog Baru')
@section('page-title', 'Buat Blog Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm p-6">
        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Judul --}}
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('title') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Masukkan judul artikel">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Isi Konten --}}
            <div class="mb-5">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="10" required
                          class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('content') border-red-500 focus:ring-red-500 @enderror"
                          placeholder="Tulis konten artikel di sini...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Thumbnail --}}
            <div class="mb-5">
                <label for="thumbnail_path" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (Opsional)</label>
                <input type="file" name="thumbnail_path" id="thumbnail_path" accept="image/*"
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('thumbnail_path') border-red-500 focus:ring-red-500 @enderror">
                <small class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti thumbnail.</small>
                @error('thumbnail_path')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="mb-5">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori (Opsional)</label>
                <select name="category_id" id="category_id"
                        class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('category_id') border-red-500 focus:ring-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required
                        class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('status') border-red-500 focus:ring-red-500 @enderror">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.blog.index') }}" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Simpan Blog
                </button>
            </div>
        </form>
    </div>
</div>
@endsection