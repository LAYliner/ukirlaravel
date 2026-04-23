{{-- resources/views/admin/projects/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Project')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Project Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Status awal akan disetel ke Draft. Slug akan di-generate otomatis dari judul.</p>
    </div>
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('admin.projects.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Project <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                    placeholder="Masukkan judul project">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Client Name --}}
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Client (Opsional)</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('client_name') border-red-500 @enderror"
                    placeholder="Nama client atau perusahaan">
                @error('client_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Project Date --}}
            <div>
                <label for="project_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Project</label>
                <input type="date" name="project_date" id="project_date" value="{{ old('project_date') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project_date') border-red-500 @enderror">
                @error('project_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="5"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                placeholder="Jelaskan detail project, material, spesifikasi, atau catatan khusus...">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
            <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                Simpan Project
            </button>
        </div>
    </form>
</div>
@endsection