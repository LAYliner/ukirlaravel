@extends('layouts.admin')

@section('title', 'Edit Tag')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <a href="{{ route('admin.tags.index') }}" class="btn btn-sm btn-secondary mb-2">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <h1 class="h3 mb-0 text-gray-800">Edit Tag: {{ $tag->name }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Tag <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tag->name) }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL Friendly)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $tag->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui Tag</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    Informasi Penggunaan
                </div>
                <div class="card-body">
                    <p>Tag ini sedang digunakan oleh <strong>{{ $tag->projects_count }}</strong> project.</p>
                    @if($tag->projects_count > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Tag ini tidak dapat dihapus selama masih digunakan oleh project.
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Tag ini aman untuk dihapus.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection