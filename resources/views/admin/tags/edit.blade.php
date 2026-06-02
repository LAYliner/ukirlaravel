@extends('layouts.admin')

@section('title', 'Edit Tag')
@section('page-title', 'Edit Tag')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm p-6">
        <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Nama Tag --}}
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Tag <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" required
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('name') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: Web Development, UI Design, Laravel">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div class="mb-6">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (Opsional)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $tag->slug) }}"
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('slug') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="web-development">
                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk generate otomatis dari nama.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.tags.index') }}" 
                   class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary border border-transparent rounded-md text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Perbarui Tag
                </button>
            </div>
        </form>
    </div>

    {{-- Informasi Penggunaan --}}
    <div class="mt-6">
        @if($tag->projects_count > 0)
            <div class="p-4 text-sm text-amber-700 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg shadow-sm" role="alert">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <span class="font-medium">Perhatian</span>
                </div>
                Tag ini sedang digunakan oleh <strong>{{ $tag->projects_count }}</strong> project. Tag tidak dapat dihapus selama masih digunakan.
            </div>
        @else
            <div class="p-4 text-sm text-green-700 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tag ini belum digunakan oleh project manapun dan aman untuk dihapus.
                </div>
            </div>
        @endif
    </div>
</div>
@endsection