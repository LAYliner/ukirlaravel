@extends('layouts.main')

@section('title', 'Blog - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-bold text-text mb-4">Blog & Artikel</h1>
        <p class="text-xl text-text/90 font-medium leading-relaxed max-w-3xl">Inspirasi, wawasan, dan cerita menarik seputar dunia seni ukir kayu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($blogs as $blog)
            <div class="bg-white border border-secondary/30 rounded-lg overflow-hidden hover:border-accent/50 transition-all duration-300 group flex flex-col shadow-sm hover:shadow-md">
                <div class="aspect-w-16 aspect-h-10 bg-secondary/10 relative">
                    @if($blog->thumbnail_path)
                        <img src="{{ asset('storage/' . $blog->thumbnail_path) }}" alt="{{ $blog->title }}" class="object-cover w-full h-64 group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="flex items-center justify-center w-full h-64 text-text/40">
                            Tanpa Thumbnail
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-center text-xs text-text/80 font-medium mb-3 gap-2">
                        <span>{{ $blog->created_at->format('d M Y') }}</span>
                        <span>•</span>
                        <span>{{ $blog->user->name ?? 'Admin' }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-text mb-2 group-hover:text-primary transition-colors">{{ $blog->title }}</h3>
                    <p class="text-text/90 font-medium text-base leading-relaxed mb-6 line-clamp-3 flex-grow">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                    <a href="{{ route('blog.show', $blog->slug) }}" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors mt-auto">
                        Baca Selengkapnya 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-secondary/5 border border-secondary/20 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-text/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20"></path></svg>
                <h3 class="text-lg font-medium text-text mb-1">Belum ada artikel</h3>
                <p class="text-text/80 font-medium">Saat ini belum ada artikel yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $blogs->links() }}
    </div>
</div>
@endsection