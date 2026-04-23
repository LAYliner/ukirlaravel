@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h3 mb-1 text-dark">Edit Project</h1>
        <p class="text-sm text-muted">Slug akan otomatis diperbarui jika judul diubah. Perubahan status hanya dapat dilakukan melalui dropdown di halaman daftar.</p>
    </div>

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PATCH')

        {{-- Preserve status untuk melewati validasi required di UpdateProjectRequest --}}
        <input type="hidden" name="status" value="{{ old('status', $project->status) }}">

        <div class="row g-3 mb-4">
            {{-- Title --}}
            <div class="col-md-6">
                <label for="title" class="form-label fw-medium">Judul Project <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}"
                    class="form-control @error('title') is-invalid @enderror" placeholder="Masukkan judul project">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Client Name --}}
            <div class="col-md-6">
                <label for="client_name" class="form-label fw-medium">Nama Client (Opsional)</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $project->client_name) }}"
                    class="form-control @error('client_name') is-invalid @enderror" placeholder="Nama client atau perusahaan">
                @error('client_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Project Date --}}
            <div class="col-md-6">
                <label for="project_date" class="form-label fw-medium">Tanggal Project</label>
                <input type="date" name="project_date" id="project_date" value="{{ old('project_date', $project->project_date?->format('Y-m-d')) }}"
                    class="form-control @error('project_date') is-invalid @enderror">
                @error('project_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Read-only Status --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">Status Saat Ini</label>
                <div class="input-group">
                    <span class="form-control-plaintext fw-semibold px-3 bg-light border rounded">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
                <div class="form-text text-muted">Ubah status melalui dropdown di halaman daftar project.</div>
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="form-label fw-medium">Deskripsi <span class="text-danger">*</span></label>
            <textarea name="description" id="description" rows="6"
                class="form-control @error('description') is-invalid @enderror"
                placeholder="Jelaskan detail project, material, spesifikasi, atau catatan khusus...">{{ old('description', $project->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Visibility Toggle --}}
        <div class="mb-4 form-check">
            <input type="checkbox" name="is_visible" id="is_visible" value="1"
                class="form-check-input @error('is_visible') is-invalid @enderror"
                {{ old('is_visible', $project->is_visible) ? 'checked' : '' }}>
            <label class="form-check-label fw-medium" for="is_visible">
                Terbitkan Langsung (Visible)
            </label>
            @error('is_visible')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection