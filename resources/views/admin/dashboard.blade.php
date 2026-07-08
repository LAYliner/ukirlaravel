@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text">Manajemen Situs</h1>
            <p class="text-gray-650 mt-1">Kelola artikel, proyek, dan konten blog dari sini.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.blog.create') }}" 
               class="inline-flex items-center gap-2 bg-primary text-white hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Blog Baru
            </a>
            <a href="{{ route('admin.blog.index') }}" 
               class="inline-flex items-center gap-2 bg-white border border-secondary/30 text-text hover:bg-gray-50 focus:ring-2 focus:ring-primary/50 px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                Lihat Semua Blog
            </a>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Total Blogs --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-medium text-gray-900 uppercase tracking-wider">Total Blog</h3>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-text">{{ $totalBlogs ?? 0 }}</p>
            <p class="text-sm text-gray-800 mt-1">Blog terpublikasi & draft</p>
        </div>

        {{-- Total Users --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-medium text-gray-900 uppercase tracking-wider">Total User</h3>
                <span class="p-2 bg-green-50 text-green-600 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-text">{{ $totalUsers ?? 0 }}</p>
            <p class="text-sm text-gray-800 mt-1">Admin, Author, & Member</p>
        </div>

        {{-- Total Comments --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-medium text-gray-900 uppercase tracking-wider">Komentar</h3>
                <span class="p-2 bg-yellow-50 text-yellow-600 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-text">{{ $totalComments ?? 0 }}</p>
            <p class="text-sm text-gray-800 mt-1">Diskusi aktif</p>
        </div>

        {{-- Total Categories --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-medium text-gray-900 uppercase tracking-wider">Kategori</h3>
                <span class="p-2 bg-purple-50 text-purple-600 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-text">{{ $totalCategories ?? 0 }}</p>
            <p class="text-sm text-gray-800 mt-1">Topik konten</p>
        </div>

    </section>
</div>
@endsection