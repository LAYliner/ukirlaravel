@extends('layouts.main')

@section('title', 'Karya Kami - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-bold text-text mb-4">Karya Kami</h1>
        <p class="text-xl text-text/90 font-medium leading-relaxed max-w-3xl">Eksplorasi portofolio ukiran terbaik kami yang telah menghiasi berbagai ruang dan bangunan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($projects as $project)
            <div class="bg-white border border-secondary/30 rounded-lg overflow-hidden hover:border-accent/50 transition-all duration-300 group flex flex-col shadow-sm hover:shadow-md">
                <div class="aspect-w-16 aspect-h-10 bg-secondary/10 relative">
                    @if($project->media && $project->media->count() > 0)
                        <img src="{{ asset('storage/' . $project->media->first()->file_path) }}" alt="{{ $project->title }}" class="object-cover w-full h-64 group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="flex items-center justify-center w-full h-64 text-text/40">
                            Tidak ada gambar
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-text mb-2 group-hover:text-primary transition-colors">{{ $project->title }}</h3>
                    <p class="text-text/90 font-medium text-base leading-relaxed mb-6 line-clamp-3 flex-grow">{{ strip_tags($project->description) }}</p>
                    <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors mt-auto">
                        Detail Karya 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-secondary/5 border border-secondary/20 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-text/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <h3 class="text-lg font-medium text-text mb-1">Belum ada karya</h3>
                <p class="text-text/80 font-medium">Saat ini belum ada karya yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $projects->links() }}
    </div>
</div>
@endsection
