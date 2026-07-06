@extends('layouts.main')

@section('title', 'Proyek Kami - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-bold text-text mb-4">Proyek Kami</h1>

        {{-- Search dan Filter Form --}}
        <form method="GET" action="{{ route('projects.index') }}" class="mt-6 flex flex-col sm:flex-row gap-4 max-w-3xl">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari proyek..."
                    class="w-full px-4 py-3 border border-secondary/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 text-text bg-white"
                >
            </div>
            <div class="sm:w-48">
                <select
                    name="tags[]"
                    multiple
                    class="w-full px-4 py-3 border border-secondary/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 text-text bg-white h-32"
                >
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, request('tags', [])) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-text/70 mt-1">Tahan Ctrl/Cmd untuk memilih beberapa tag</p>
            </div>
            <button
                type="submit"
                class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-colors duration-300 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
            @if(request('search') || request('tags'))
                <a
                    href="{{ route('projects.index') }}"
                    class="px-6 py-3 bg-secondary/20 text-text font-medium rounded-lg hover:bg-secondary/30 transition-colors duration-300 flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </a>
            @endif
        </form>
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

                    {{-- Tags --}}
                    @if($project->tags && $project->tags->count() > 0)
                        <div class="mb-3 flex flex-wrap gap-1.5">
                            @foreach($project->tags as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <p class="text-text/90 font-medium text-base leading-relaxed mb-6 line-clamp-3 flex-grow">{{ strip_tags($project->description) }}</p>
                    <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors mt-auto">
                        Detail Proyek
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