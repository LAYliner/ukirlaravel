@extends('layouts.admin')

@section('title', 'Manajemen Tags')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Tags</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Tag Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <form action="{{ route('admin.tags.index') }}" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari nama tag..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>
                                <a href="{{ route('admin.tags.index', ['sort' => 'name', 'direction' => request('direction') == 'asc' && request('sort') == 'name' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Nama Tag
                                    @if(request('sort') == 'name')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Slug</th>
                            <th>
                                <a href="{{ route('admin.tags.index', ['sort' => 'projects_count', 'direction' => request('direction') == 'asc' && request('sort') == 'projects_count' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                    Jumlah Project
                                    @if(request('sort') == 'projects_count')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Dibuat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $index => $tag)
                            <tr>
                                <td>{{ $tags->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-primary/10 text-primary px-3 py-2 rounded-pill fw-bold">
                                        {{ $tag->name }}
                                    </span>
                                </td>
                                <td><code>{{ $tag->slug }}</code></td>
                                <td class="text-center">{{ $tag->projects_count }}</td>
                                <td>{{ $tag->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tag ini? Pastikan tag tidak digunakan oleh project manapun.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" {{ $tag->projects_count > 0 ? 'disabled' : '' }} title="{{ $tag->projects_count > 0 ? 'Tag masih digunakan' : 'Hapus' }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada data tag.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</div>
@endsection