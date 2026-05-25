@extends('layouts.admin')

@section('title', 'Manajemen Blog')
@section('page-title', 'Blog')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-text">Daftar Blog</h1>
        <a href="{{ route('admin.blog.create') }}" 
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

    {{-- Filters & Search --}}
    <div class="bg-white p-4 rounded-lg border border-secondary/30 shadow-sm">
        <form action="{{ route('admin.blog.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Blog</label>
                <input type="text"
                       name="search"
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Cari judul blog..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
            </div>

            {{-- Category Filter --}}
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            {{-- Submit & Reset --}}
            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 text-sm font-medium transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 text-sm font-medium transition shadow-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Sorting Info --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Menampilkan {{ $blogs->firstItem() ?? 0 }} - {{ $blogs->lastItem() ?? 0 }} dari {{ $blogs->total() }} blog
        </p>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Urutkan:</span>
            <a href="{{ route('admin.blog.index', array_merge(request()->except('direction'), ['sort' => 'title', 'direction' => request('sort') === 'title' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'title' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Judul {{ request('sort') === 'title' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.blog.index', array_merge(request()->except('direction'), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'created_at' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Tanggal {{ request('sort') === 'created_at' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text">
                                {{ $blog->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $blog->category->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.blog.update-status', $blog->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        {{-- Preserve query params --}}
                                        @foreach(request()->except(['page', '_token', '_method']) as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                        @endforeach

                                        <select name="status"
                                                onchange="this.form.submit(); this.disabled=true;"
                                                class="text-xs font-medium rounded-full px-2.5 py-0.5 border-0 cursor-pointer focus:ring-2 focus:ring-primary/50 {{ $blog->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <option value="draft" {{ $blog->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ $blog->status === 'published' ? 'selected' : '' }}>Published</option>
                                        </select>
                                    </form>
                                    @if($blog->status === 'published' && !$blog->is_visible)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Hidden</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blog->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                    @if($blog->status === 'published' && !$blog->is_visible)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Hidden</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $blog->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $blog->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.blog.edit', $blog->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                    @if(auth()->user()->role === 'admin' && $blog->status === 'published')
                                        <form action="{{ route('admin.blog.toggle-visibility', $blog->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @foreach(request()->except(['page', '_token', '_method']) as $key => $value)
                                                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                            @endforeach
                                            <button type="submit"
                                                    class="text-blue-600 hover:text-blue-900"
                                                    title="{{ $blog->is_visible ? 'Sembunyikan' : 'Tampilkan' }}">
                                                {{ $blog->is_visible ? 'Hide' : 'Show' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus blog ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada blog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($blogs, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $blogs->appends(request()->except('page'))->links('vendor.pagination.simple') }}
            </div>
        @endif
    </div>
</div>
@endsection