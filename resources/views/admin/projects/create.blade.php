@extends('layouts.admin')

@section('title', 'Tambah Project')
@section('page-title', 'Tambah Project Baru')

@push('scripts')
    @vite(['resources/css/ckeditor.css', 'resources/js/ckeditor.js'])
@endpush

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm p-6">
        <p class="text-sm text-gray-500 mb-6">
            Status awal akan disetel ke Draft. Slug akan di-generate otomatis dari judul.
        </p>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Project <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('title') border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Masukkan judul project">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Client Name --}}
                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Client (Opsional)</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('client_name') border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Nama client atau perusahaan">
                    @error('client_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Thumbnail Path --}}
                <div>
                    <label for="thumbnail_path" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (Opsional)</label>
                    <input type="file" name="thumbnail_path" id="thumbnail_path" accept="image/*"
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('thumbnail_path') border-red-500 focus:ring-red-500 @enderror">
                    <div class="mt-2 flex items-center">
                        <input type="checkbox" name="delete_thumbnail" id="delete_thumbnail" value="1"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="delete_thumbnail" class="ml-2 block text-sm text-gray-700">
                            Hapus thumbnail yang ada
                        </label>
                    </div>
                    @error('thumbnail_path')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <div id="editor-container" class="main-container">
                    <div class="editor-container_classic-editor">
                        <div class="editor-container__editor">
                            <textarea name="description" id="editor">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tags (Multi-select) --}}
            <div class="mb-6">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags (Pilih beberapa)</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-64 overflow-y-auto p-3 border border-gray-300 rounded-md bg-white">
                    @foreach($tags as $tag)
                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox"
                                   name="tags[]"
                                   value="{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Centang tag yang sesuai dengan project ini.</p>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.projects.index') }}"
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Simpan Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection