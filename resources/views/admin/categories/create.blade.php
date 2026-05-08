@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            {{-- Nama Kategori --}}
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('name') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: Ukiran Kayu Jati">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div class="mb-5">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (Opsional)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('slug') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="contoh-ukiran-kayu-jati">
                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk generate otomatis dari nama.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('description') border-red-500 focus:ring-red-500 @enderror"
                          placeholder="Jelaskan singkat tentang kategori ini...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection