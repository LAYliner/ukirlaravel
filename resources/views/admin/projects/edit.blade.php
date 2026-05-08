@extends('layouts.admin')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

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

                {{-- Project Date --}}
                <div>
                    <label for="project_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Project</label>
                    <input type="date" name="project_date" id="project_date" value="{{ old('project_date', $project->project_date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('project_date') border-red-500 focus:ring-red-500 @enderror">
                    @error('project_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                <textarea name="description" id="description" rows="6" required
                          class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('description') border-red-500 focus:ring-red-500 @enderror"
                          placeholder="Jelaskan detail project, material, spesifikasi, atau catatan khusus...">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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