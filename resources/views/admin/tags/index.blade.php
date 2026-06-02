@extends('layouts.admin')

@section('title', 'Manajemen Tags')
@section('page-title', 'Tags')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-text">Daftar Tags</h1>
        <a href="{{ route('admin.tags.create') }}" 
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
        <form method="GET" action="{{ route('admin.tags.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" placeholder="Cari nama tag..." value="{{ request('search') }}"
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @php
                                $isNameSort = request('sort') === 'name';
                                $nameDirection = $isNameSort && request('direction') === 'asc' ? 'desc' : 'asc';
                                $nameSortIcon = $isNameSort ? (request('direction') === 'asc' ? '↑' : '↓') : '↕';
                                $nameSortColor = $isNameSort ? 'text-primary' : 'text-gray-300';
                            @endphp
                            <a href="{{ route('admin.tags.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'name', 'direction' => $nameDirection])) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Nama
                                <span class="{{ $nameSortColor }}">{{ $nameSortIcon }}</span>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Slug
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @php
                                $isCountSort = request('sort') === 'projects_count';
                                $countDirection = $isCountSort && request('direction') === 'asc' ? 'desc' : 'asc';
                                $countSortIcon = $isCountSort ? (request('direction') === 'asc' ? '↑' : '↓') : '↕';
                                $countSortColor = $isCountSort ? 'text-primary' : 'text-gray-300';
                            @endphp
                            <a href="{{ route('admin.tags.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'projects_count', 'direction' => $countDirection])) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Jumlah Project
                                <span class="{{ $countSortColor }}">{{ $countSortIcon }}</span>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tags as $index => $tag)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $tags->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text">
                                {{ $tag->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <code class="px-2 py-0.5 bg-gray-100 rounded text-xs">{{ $tag->slug }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $tag->projects_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $tag->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.tags.edit', $tag) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus tag ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" {{ $tag->projects_count > 0 ? 'disabled title=Tag masih digunakan' : '' }}>Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Tidak ada tag ditemukan. 
                                <a href="{{ route('admin.tags.create') }}" class="text-primary hover:underline">Buat baru?</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($tags, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $tags->appends(request()->except('page'))->links('vendor.pagination.simple') }}
            </div>
        @endif
    </div>
</div>
@endsection