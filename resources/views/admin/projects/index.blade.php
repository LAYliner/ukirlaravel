@extends('layouts.admin')

@section('title', 'Manajemen Projects')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Daftar Projects</h1>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Tambah Project</a>
    </div>

    {{-- Filter & Search Form --}}
    <form method="GET" action="{{ route('admin.projects.index') }}" class="card p-3 mb-4 bg-light">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label fw-medium">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium">Author</label>
                <select name="author_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ request('author_id') === $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-secondary">Terapkan Filter</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari judul/client..." value="{{ request('q') }}">
            </div>
        </div>
    </form>

    {{-- Data Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'dir' => request('sort') === 'title' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Judul @if(request('sort') === 'title') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'client_name', 'dir' => request('sort') === 'client_name' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Client @if(request('sort') === 'client_name') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'dir' => request('sort') === 'views' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Views @if(request('sort') === 'views') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => request('sort') === 'created_at' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Dibuat @if(request('sort') === 'created_at') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
                        </a>
                    </th>
                    <th>Status</th>
                    <th width="250">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $index => $project)
                <tr>
                    <td>{{ $projects->firstItem() + $index }}</td>
                    <td class="fw-medium">{{ $project->title }}</td>
                    <td>{{ $project->client_name ?? '<span class="text-muted">-</span>' }}</td>
                    <td>{{ number_format($project->views) }}</td>
                    <td>{{ $project->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('admin.projects.update-status', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                {{-- Preserve query params agar filter/pagination tidak reset --}}
                                @foreach(request()->except(['page', '_token', '_method']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                @endforeach
                                
                                <select name="status" 
                                        class="form-select form-select-sm {{ $project->status === 'published' ? 'bg-success text-white border-success' : 'border-primary' }}"
                                        onchange="this.form.submit(); this.disabled=true;">
                                    <option value="draft" {{ $project->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $project->status === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </form>
                            @if($project->status === 'published' && !$project->is_visible)
                                <span class="badge bg-warning text-dark ms-1">Hidden</span>
                            @endif
                        @else
                            <span class="badge bg-{{ $project->status === 'published' ? 'success' : 'secondary' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                            @if($project->status === 'published' && !$project->is_visible)
                                <span class="badge bg-warning text-dark ms-1">Hidden</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            
                            @if(auth()->user()->role === 'admin' && $project->status === 'published')
                                <form action="{{ route('admin.projects.toggle-visibility', $project->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @foreach(request()->except(['page', '_token', '_method']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                    @endforeach
                                <button type="submit" class="btn btn-sm btn-outline-info" title="{{ $project->is_visible ? 'Sembunyikan' : 'Tampilkan' }}">
                                    {{ $project->is_visible ? 'Sembunyikan' : 'Tampilkan' }}
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan project ke trash? Data dapat dipulihkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada data project yang sesuai filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <span class="text-muted small">Menampilkan {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }} dari {{ $projects->total() }} data</span>
        {{ $projects->links('vendor.pagination.simple') }}
    </div>
</div>
@endsection