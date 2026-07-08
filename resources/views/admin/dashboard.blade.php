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
            <p class="text-sm text-gray-800 mt-1">Blog terpublikasi</p>
        </div>

        {{-- Total Projects --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-medium text-gray-900 uppercase tracking-wider">Total Proyek</h3>
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-text">{{ $totalProjects ?? 0 }}</p>
            <p class="text-sm text-gray-800 mt-1">Proyek terpublikasi</p>
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
            <p class="text-sm text-gray-800 mt-1">Pengguna terdaftar</p>
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

    </section>

    {{-- Chart & Recent Comments Section --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Views Chart --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-text">Grafik Views Terbanyak</h2>
                    <p class="text-xs text-gray-500">Perbandingan jumlah tayangan Blog & Proyek terpopuler</p>
                </div>
                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">Top 5</span>
            </div>
            <div class="relative h-[320px]">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        {{-- Recent Comments --}}
        <div class="bg-white p-6 rounded-lg border border-secondary/30 shadow-sm hover:shadow-md transition-shadow lg:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-text">10 Komentar Terbaru</h2>
                    <p class="text-xs text-gray-500">Diskusi dan tanggapan terbaru dari pengguna</p>
                </div>
                <span class="text-xs font-semibold text-primary bg-primary/10 px-2.5 py-0.5 rounded-full">Log</span>
            </div>
            <div class="space-y-4 max-h-[320px] overflow-y-auto pr-1 custom-scrollbar">
                @forelse($recentComments as $comment)
                    <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-100">
                        <div class="flex justify-between items-start mb-1 gap-2">
                            <span class="font-semibold text-sm text-gray-800">{{ $comment->user->name ?? 'Anonim' }}</span>
                            <span class="text-xs text-gray-500 shrink-0">{{ $comment->created_at->locale('id')->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-600 line-clamp-2 mb-2">
                            "{{ Str::limit($comment->content, 100) }}"
                        </p>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-gray-400">
                                Pada:
                                <span class="font-medium text-gray-500">
                                    @if($comment->commentable_type === 'App\Models\Blog')
                                        Blog ({{ Str::limit($comment->commentable->title ?? '', 15) }})
                                    @elseif($comment->commentable_type === 'App\Models\Project')
                                        Proyek ({{ Str::limit($comment->commentable->title ?? '', 15) }})
                                    @else
                                        Konten
                                    @endif
                                </span>
                            </span>
                            <a href="{{ $comment->commentable_type === 'App\Models\Blog' ? route('blog.show', $comment->commentable->slug ?? '') : ($comment->commentable_type === 'App\Models\Project' ? route('projects.show', $comment->commentable->slug ?? '') : '#') }}"
                               target="_blank"
                               class="text-primary hover:underline font-medium inline-flex items-center gap-1">
                                Lihat
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-sm">Belum ada komentar terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('viewsChart').getContext('2d');

        const blogViews = {!! json_encode($mostViewedBlogs->pluck('views')) !!};
        const blogTitles = {!! json_encode($mostViewedBlogs->pluck('title')) !!};

        const projectViews = {!! json_encode($mostViewedProjects->pluck('views')) !!};
        const projectTitles = {!! json_encode($mostViewedProjects->pluck('title')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Peringkat 1', 'Peringkat 2', 'Peringkat 3', 'Peringkat 4', 'Peringkat 5'],
                datasets: [
                    {
                        label: 'Blog',
                        data: blogViews,
                        backgroundColor: 'rgba(59, 130, 246, 0.85)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                    },
                    {
                        label: 'Proyek',
                        data: projectViews,
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'sans-serif',
                                size: 12
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return `Peringkat ${index + 1}`;
                            },
                            label: function(context) {
                                const datasetIndex = context.datasetIndex;
                                const index = context.dataIndex;
                                const views = context.raw;
                                if (datasetIndex === 0) {
                                    const title = blogTitles[index] || 'Tidak ada data';
                                    return ` Blog: ${title} (${views} views)`;
                                } else {
                                    const title = projectTitles[index] || 'Tidak ada data';
                                    return ` Proyek: ${title} (${views} views)`;
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 20px;
    }
</style>
@endpush
@endsection