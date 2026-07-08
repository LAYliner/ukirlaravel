@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-text">Daftar User</h1>
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
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                <input type="text"
                       name="search"
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
            </div>

            {{-- Role Filter --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="author" {{ request('role') === 'author' ? 'selected' : '' }}>Author</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary text-sm">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            {{-- Submit & Reset --}}
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 text-sm font-medium transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 text-sm font-medium transition shadow-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Sorting Info --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
        </p>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm text-gray-600">Urutkan:</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('direction'), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'name' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Nama {{ request('sort') === 'name' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('direction'), ['sort' => 'email', 'direction' => request('sort') === 'email' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'email' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Email {{ request('sort') === 'email' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('direction'), ['sort' => 'role', 'direction' => request('sort') === 'role' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'role' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Role {{ request('sort') === 'role' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('direction'), ['sort' => 'is_active', 'direction' => request('sort') === 'is_active' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'is_active' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Status {{ request('sort') === 'is_active' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('direction'), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
               class="text-sm {{ request('sort') === 'created_at' ? 'font-bold text-primary' : 'text-gray-600 hover:text-primary' }}">
                Tanggal {{ request('sort') === 'created_at' ? (request('direction') === 'desc' ? '↓' : '↑') : '' }}
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('direction'), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'desc' ? 'asc' : 'desc'])) }}"
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors {{ $user->trashed() ? 'bg-red-50' : '' }}">
                            {{-- Profile Photo --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                         alt="{{ $user->name }}"
                                         class="h-12 w-12 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold border-2 border-gray-200">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                @if($user->phone)
                                    <div class="text-xs text-gray-500">{{ $user->phone }}</div>
                                @endif
                            </td>

                            {{-- Email --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>

                            {{-- Role --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role"
                                                onchange="this.form.submit()"
                                                class="text-sm px-2 py-1 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>Author</option>
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">{{ $user->role }}</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->trashed())
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Deleted</span>
                                @elseif($user->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Non-Aktif</span>
                                @endif
                            </td>

                            {{-- Timestamps --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-xs">Created: {{ $user->created_at->format('d M Y, H:i') }}</div>
                                @if($user->updated_at->ne($user->created_at))
                                    <div class="text-xs">Updated: {{ $user->updated_at->format('d M Y, H:i') }}</div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @if($user->trashed())
                                    {{-- Restore Button --}}
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Pulihkan">Restore</button>
                                    </form>
                                    {{-- Force Delete Button --}}
                                    <button type="button"
                                            onclick="confirmForceDelete('{{ route('admin.users.force-delete', $user->id) }}')"
                                            class="text-red-800 hover:text-red-900"
                                            title="Hapus Permanen">Delete Perm</button>
                                @else
                                    {{-- Toggle Status Button --}}
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                onclick="confirmToggleStatus('{{ route('admin.users.toggle-status', $user->id) }}', {{ $user->is_active ? 'true' : 'false' }})"
                                                class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}"
                                                title="{{ $user->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                            {{ $user->is_active ? 'Non-Aktif' : 'Aktif' }}
                                        </button>
                                    @endif

                                    {{-- Delete Button --}}
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}')"
                                                class="text-red-600 hover:text-red-900"
                                                title="Hapus User">
                                            Hapus
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                Tidak ada user yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($users, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $users->appends(request()->except('page'))->links('vendor.pagination.simple') }}
            </div>
        @endif
    </div>
</div>

{{-- Confirmation Modals --}}
<script>
function confirmDelete(deleteUrl) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini adalah soft-delete dan user dapat dipulihkan.')) {
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
    if (confirm('PERINGATAN: Apakah Anda yakin ingin menghapus user ini secara permanen? Tindakan ini tidak dapat dibatalkan!')) {
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

function confirmToggleStatus(toggleUrl, isActive) {
    const actionText = isActive ? 'non-aktif' : 'aktif';
    if (confirm('Apakah Anda yakin ingin mengubah status user menjadi ' + actionText + '?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = toggleUrl;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection