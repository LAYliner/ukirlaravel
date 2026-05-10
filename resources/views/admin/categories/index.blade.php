@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-text">Daftar Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center gap-2 bg-primary text-white hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Baru
        </a>
    </div>

        {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter --}}
    <div class="bg-white p-4 rounded-lg border border-secondary/30 shadow-sm">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" placeholder="Cari nama kategori..." value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border border-secondary/30 rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                Cari
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @php
                                $currentSort = request('sort_direction', 'asc');
                                $nextSortDirection = $currentSort === 'asc' ? 'desc' : 'asc';
                                $sortIcon = $currentSort === 'asc' ? '↑' : '↓';
                            @endphp
                            <a href="{{ route('admin.categories.index', array_merge(request()->except('sort_direction'), ['sort_direction' => $nextSortDirection])) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Nama
                                @if(request()->has('sort_direction'))
                                    <span class="text-primary">{{ $sortIcon }}</span>
                                @else
                                    <span class="text-gray-300">↕</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::limit($category->description, 50) ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                Tidak ada kategori ditemukan. 
                                <a href="{{ route('admin.categories.create') }}" class="text-primary hover:underline">Buat baru?</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($categories, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $categories->appends(request()->except('page'))->links('vendor.pagination.simple') }}
            </div>
        @endif
    </div>
</div>
@endsection