@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <h1 style="margin-bottom: 1rem;">Manajemen Situs</h1>
    <p style="margin-bottom: 1.5rem;">Kelola artikel dan konten blog dari sini.</p>
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.blog.create') }}" style="background: #27ae60; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 4px; margin-right: 0.5rem;">+ Buat Artikel Baru</a>
        <a href="{{ route('admin.blog.index') }}" style="background: #3498db; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 4px;">Lihat Semua Artikel</a>
    </div>
    
    <div style="background: white; padding: 1.5rem; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 1rem;">Statistik</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3 style="color: #2c3e50;">{{ $totalBlogs ?? 0 }}</h3>
                <small style="color: #7f8c8d;">Total Artikel</small>
            </div>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3 style="color: #2c3e50;">{{ $totalUsers ?? 0 }}</h3>
                <small style="color: #7f8c8d;">Total User</small>
            </div>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3 style="color: #2c3e50;">{{ $totalComments ?? 0 }}</h3>
                <small style="color: #7f8c8d;">Komentar</small>
            </div>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3 style="color: #2c3e50;">{{ $totalCategories ?? 0 }}</h3>
                <small style="color: #7f8c8d;">Kategori</small>
            </div>
        </div>
    </div>
</div>
@endsection