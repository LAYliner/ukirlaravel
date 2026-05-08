@extends('layouts.admin')

@section('title', 'Comment Logs & Moderation')
@section('page-title', 'Moderasi Komentar')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-text">Daftar Komentar</h1>
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
        <form action="{{ route('admin.comments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Komentar</label>
                <input type="text"
                       name="search"
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Cari isi komentar, nama, atau email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
            </div>

            {{-- Type Filter --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="blog" {{ request('type') === 'blog' ? 'selected' : '' }}>Blog</option>
                    <option value="project" {{ request('type') === 'project' ? 'selected' : '' }}>Project</option>
                </select>
            </div>

            {{-- Blog Filter --}}
            <div>
                <label for="blog_id" class="block text-sm font-medium text-gray-700 mb-1">Blog</label>
                <select name="blog_id" id="blog_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua Blog</option>
                    @foreach($blogs as $blog)
                        <option value="{{ $blog->id }}" {{ request('blog_id') == $blog->id ? 'selected' : '' }}>
                            {{ Str::limit($blog->title, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Project Filter --}}
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                <select name="project_id" id="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ Str::limit($project->title, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Aktif</option>
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua (termasuk deleted)</option>
                    <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted Only</option>
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
                <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 text-sm font-medium transition shadow-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Sorting Info --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Menampilkan {{ $comments->firstItem() ?? 0 }} - {{ $comments->lastItem() ?? 0 }} dari {{ $comments->total() }} komentar
        </p>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Urutkan:</span>
            <a href="{{ route('admin.comments.index', array_merge(request()->except('direction'), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'created_at' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Tanggal {{ request('sort') === 'created_at' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.comments.index', array_merge(request()->except('direction'), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'id' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                ID {{ request('sort') === 'id' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-secondary/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blog/Project</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi Komentar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($comments as $comment)
                        <tr class="hover:bg-gray-50 transition-colors {{ $comment->trashed() ? 'bg-red-50' : '' }}">
                            {{-- Type --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(str_contains($comment->commentable_type, 'Blog'))
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Blog</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Project</span>
                                @endif
                            </td>

                            {{-- Blog/Project Title --}}
                            <td class="px-6 py-4 text-sm">
                                @if($comment->commentable)
                                    <div class="font-medium text-gray-900">{{ Str::limit($comment->commentable->title ?? 'N/A', 40) }}</div>
                                    <div class="text-gray-500 text-xs">{{ str_contains($comment->commentable_type, 'Blog') ? 'Blog' : 'Project' }}</div>
                                @else
                                    <span class="text-gray-400 text-xs">Entity Deleted</span>
                                @endif
                            </td>

                            {{-- User --}}
                            <td class="px-6 py-4 text-sm">
                                @if($comment->user)
                                    <div class="font-medium text-gray-900">{{ $comment->user->name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $comment->user->email }}</div>
                                @else
                                    <span class="text-gray-400 text-xs">Guest / Deleted User</span>
                                @endif
                            </td>

                            {{-- Content --}}
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $comment->content }}">
                                {{ Str::limit($comment->content, 50) }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($comment->trashed())
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Deleted</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @endif
                            </td>

                            {{-- Timestamps --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-xs">Created: {{ $comment->created_at->format('d M Y, H:i') }}</div>
                                @if($comment->updated_at->ne($comment->created_at))
                                    <div class="text-xs">Updated: {{ $comment->updated_at->format('d M Y, H:i') }}</div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @if($comment->trashed())
                                    {{-- Restore Button --}}
                                    <form action="{{ route('admin.comments.restore', $comment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Pulihkan">
                                            Restore
                                        </button>
                                    </form>
                                    {{-- Force Delete Button --}}
                                    <button type="button"
                                            onclick="confirmForceDelete('{{ route('admin.comments.force-delete', $comment->id) }}')"
                                            class="text-red-800 hover:text-red-900"
                                            title="Hapus Permanen">
                                        Delete Perm
                                    </button>
                                @else
                                    {{-- Delete Button --}}
                                    <button type="button"
                                            onclick="confirmDelete('{{ route('admin.comments.destroy', $comment->id) }}')"
                                            class="text-red-600 hover:text-red-900"
                                            title="Hapus Komentar">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                Tidak ada komentar yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($comments, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $comments->appends(request()->except('page'))->links('vendor.pagination.simple') }}
            </div>
        @endif
    </div>
</div>

{{-- Confirmation Modals --}}
<script>
function confirmDelete(deleteUrl) {
    if (confirm('Apakah Anda yakin ingin menghapus komentar ini? Tindakan ini adalah soft-delete dan komentar dapat dipulihkan.')) {
        // Create a form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmForceDelete(deleteUrl) {
    if (confirm('PERINGATAN: Apakah Anda yakin ingin menghapus komentar ini secara permanen? Tindakan ini tidak dapat dibatalkan!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection