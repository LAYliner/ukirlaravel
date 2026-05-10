@extends('layouts.admin')

@section('title', 'Manajemen Proyek')
@section('page-title', 'Proyek')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-text">Daftar Proyek</h1>
        <a href="{{ route('admin.projects.create') }}" 
           class="inline-flex items-center gap-2 bg-primary text-white hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Proyek
        </a>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-gray-50 p-4 rounded-lg border border-secondary/30 shadow-sm">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Status Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">Semua</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            {{-- Author Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Author</label>
                <select name="author_id" class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">Semua</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
            </div>

            {{-- Search & Actions --}}
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                <input type="text" name="q" placeholder="Cari judul/client..." value="{{ request('q') }}"
                       class="w-full px-3 py-2 border border-secondary/30 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                <div class="flex gap-2">
                    <button type="submit" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-medium">
                        Filter
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition text-sm font-medium">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'dir' => request('sort') === 'title' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Judul
                                @if(request('sort') === 'title')
                                    <span class="text-primary">{{ request('dir') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'client_name', 'dir' => request('sort') === 'client_name' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Client
                                @if(request('sort') === 'client_name')
                                    <span class="text-primary">{{ request('dir') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'dir' => request('sort') === 'views' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Views
                                @if(request('sort') === 'views')
                                    <span class="text-primary">{{ request('dir') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => request('sort') === 'created_at' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center gap-1 hover:text-primary transition-colors">
                                Dibuat
                                @if(request('sort') === 'created_at')
                                    <span class="text-primary">{{ request('dir') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $index => $project)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $projects->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text">
                                {{ $project->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $project->client_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($project->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $project->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.projects.update-status', $project->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        {{-- Preserve query params --}}
                                        @foreach(request()->except(['page', '_token', '_method']) as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                        @endforeach
                                        
                                        <select name="status" 
                                                onchange="this.form.submit(); this.disabled=true;"
                                                class="text-xs font-medium rounded-full px-2.5 py-0.5 border-0 cursor-pointer focus:ring-2 focus:ring-primary/50 {{ $project->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <option value="draft" {{ $project->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ $project->status === 'published' ? 'selected' : '' }}>Published</option>
                                        </select>
                                    </form>
                                    @if($project->status === 'published' && !$project->is_visible)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Hidden</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                    @if($project->status === 'published' && !$project->is_visible)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Hidden</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    
                                    @if(auth()->user()->role === 'admin' && $project->status === 'published')
                                        <form action="{{ route('admin.projects.toggle-visibility', $project->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @foreach(request()->except(['page', '_token', '_method']) as $key => $value)
                                                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                            @endforeach
                                            <button type="submit" 
                                                    class="text-blue-600 hover:text-blue-900" 
                                                    title="{{ $project->is_visible ? 'Sembunyikan' : 'Tampilkan' }}">
                                                {{ $project->is_visible ? 'Hide' : 'Show' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Pindahkan project ke trash? Data dapat dipulihkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                Tidak ada data project yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($projects, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="text-sm text-gray-500">
                    Menampilkan {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }} dari {{ $projects->total() }} data
                </span>
                {{ $projects->appends(request()->except('page'))->links('vendor.pagination.simple') }}
            </div>
        @endif
    </div>
</div>
@endsection