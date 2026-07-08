@extends('layouts.admin')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@push('scripts')
    @vite(['resources/css/ckeditor.css', 'resources/js/ckeditor.js'])
@endpush

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm p-6">
        <p class="text-sm text-gray-500 mb-6">
            Slug akan otomatis diperbarui jika judul diubah. Perubahan status hanya dapat dilakukan melalui dropdown di halaman daftar.
        </p>

        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PATCH')

            {{-- Preserve status untuk melewati validasi required di UpdateProjectRequest --}}
            <input type="hidden" name="status" value="{{ old('status', $project->status) }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Project <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" required
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('title') border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Masukkan judul project">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Client Name --}}
                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Client (Opsional)</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $project->client_name) }}"
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('client_name') border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Nama client atau perusahaan">
                    @error('client_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Thumbnail Path --}}
                <div>
                    <label for="thumbnail_path" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (Opsional)</label>
                    @if($project->thumbnail_path)
                        <div class="mb-2 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $project->thumbnail_path) }}" alt="Thumbnail" class="w-48 h-auto rounded-md object-cover border border-secondary/30">
                            <button type="button" id="remove_thumbnail_btn"
                                    class="px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Hapus Thumbnail
                            </button>
                        </div>
                        <input type="hidden" name="delete_thumbnail" id="delete_thumbnail" value="0">
                    @endif
                    <input type="file" name="thumbnail_path" id="thumbnail_path" accept="image/*"
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('thumbnail_path') border-red-500 focus:ring-red-500 @enderror">
                    <small class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti thumbnail.</small>
                    @error('thumbnail_path')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const removeBtn = document.getElementById('remove_thumbnail_btn');
                        const deleteInput = document.getElementById('delete_thumbnail');

                        if (removeBtn) {
                            removeBtn.addEventListener('click', function() {
                                // Sembunyikan gambar dan tombol
                                this.parentElement.style.display = 'none';
                                // Set flag untuk menghapus thumbnail
                                deleteInput.value = '1';
                            });
                        }
                    });
                </script>

                {{-- Read-only Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Saat Ini</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm font-semibold text-gray-700">
                        {{ ucfirst($project->status) }}
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Ubah status melalui dropdown di halaman daftar project.</p>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <div id="editor-container" class="main-container">
                    <div class="editor-container_classic-editor">
                        <div class="editor-container__editor">
                            <textarea name="description" id="editor">{{ old('description', $project->description) }}</textarea>
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
                                   {{ in_array($tag->id, old('tags', $project->tags->pluck('id')->toArray())) ? 'checked' : '' }}
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

            {{-- Visibility Toggle --}}
            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_visible" id="is_visible" value="1"
                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded @error('is_visible') border-red-500 @enderror"
                       {{ old('is_visible', $project->is_visible) ? 'checked' : '' }}>
                <label for="is_visible" class="ml-2 block text-sm font-medium text-gray-700">
                    Terbitkan Langsung (Visible)
                </label>
                @error('is_visible')
                    <p class="ml-2 mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.projects.index') }}"
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection