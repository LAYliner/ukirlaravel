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

            {{-- Custom Tag Selector untuk Tags --}}
            <div class="relative w-full sm:w-64" data-tag-selector>
                {{-- Trigger button --}}
                <button
                    type="button"
                    data-tag-trigger
                    class="w-full flex items-center justify-between gap-2 px-4 py-3 border border-secondary/30 rounded-lg bg-white text-left focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 hover:border-primary/40 transition-colors"
                >
                    <span data-tag-label class="text-sm text-text truncate">
                        @php
                            $selectedTags = $tags->filter(fn($tag) => in_array($tag->id, request('tags', [])));
                        @endphp
                        @if($selectedTags->count() === 0)
                            Pilih tag
                        @elseif($selectedTags->count() <= 2)
                            {{ $selectedTags->pluck('name')->join(', ') }}
                        @else
                            {{ $selectedTags->count() }} tag dipilih
                        @endif
                    </span>
                    <svg data-tag-chevron class="w-4 h-4 text-text/50 shrink-0 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div
                    data-tag-panel
                    class="hidden absolute z-30 mt-2 w-full sm:min-w-64 bg-white border border-secondary/30 rounded-lg shadow-lg overflow-hidden left-0 right-0"
                >
                    {{-- Search (muncul otomatis kalau tag > 6) --}}
                    @if($tags->count() > 6)
                    <div class="p-2 border-b border-secondary/20">
                        <input
                            type="text"
                            data-tag-search
                            placeholder="Cari tag..."
                            class="w-full px-3 py-2 text-sm border border-secondary/30 rounded-md focus:outline-none focus:ring-2 focus:ring-primary/40"
                        >
                    </div>
                    @endif

                    {{-- List tag --}}
                    <div class="max-h-56 overflow-y-auto p-2" data-tag-list>
                        @forelse($tags as $tag)
                            <label
                                data-tag-item
                                data-tag-name="{{ Str::lower($tag->name) }}"
                                class="flex items-center gap-2.5 px-2.5 py-2 rounded-md hover:bg-primary/5 cursor-pointer select-none"
                            >
                                <span class="relative flex items-center justify-center w-4.5 h-4.5 shrink-0">
                                    <input
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        data-tag-checkbox
                                        data-tag-name-label="{{ $tag->name }}"
                                        {{ in_array($tag->id, request('tags', [])) ? 'checked' : '' }}
                                        class="peer appearance-none w-[18px] h-[18px] border-2 border-secondary/40 rounded checked:bg-primary checked:border-primary transition-colors cursor-pointer"
                                    >
                                    <svg class="absolute w-3 h-3 text-white pointer-events-none hidden peer-checked:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span class="text-sm text-text">{{ $tag->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-text/50 px-2.5 py-2">Belum ada tag</p>
                        @endforelse
                        <p data-tag-empty class="hidden text-sm text-text/50 px-2.5 py-2">Tag tidak ditemukan</p>
                    </div>

                    {{-- Footer aksi --}}
                    <div class="flex items-center justify-between p-2 border-t border-secondary/20 bg-secondary/5">
                        <button type="button" data-tag-clear class="text-xs font-medium text-text/60 hover:text-red-500 px-2 py-1.5 transition-colors">
                            Hapus semua
                        </button>
                        <button type="button" data-tag-apply class="text-xs font-medium text-white bg-primary hover:bg-primary/90 rounded-md px-3 py-1.5 transition-colors">
                            Terapkan
                        </button>
                    </div>
                </div>
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