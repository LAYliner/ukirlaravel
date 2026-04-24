@extends('layouts.main')

@push('styles')
<style>
    .bg-hero {
        background-image: linear-gradient(to bottom right, var(--color-secondary), var(--color-secondary));
    }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="bg-hero min-h-screen flex items-center pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-3xl">
                <h1 class="text-5xl md:text-6xl font-bold text-text leading-tight mb-6">
                    Seni Ukir Tradisional <br>
                    <span class="text-primary">Berkualitas Tinggi</span>
                </h1>
                <p class="text-lg md:text-xl text-text font-medium mb-10 max-w-2xl leading-relaxed">
                    Menghadirkan keindahan kayu ke dalam kehidupan Anda melalui mahakarya ukiran tangan dari para pengrajin terbaik.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('projects.index') }}" class="inline-flex justify-center items-center px-8 py-4 text-base font-semibold text-background bg-primary rounded hover:bg-accent transition-colors duration-200 shadow-md">
                        Lihat Karya Kami
                    </a>
                    <a href="#karya-terbaru" class="inline-flex justify-center items-center px-8 py-4 text-base font-semibold text-primary border border-primary rounded hover:bg-primary/5 transition-colors duration-200">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Karya Terbaru Section --}}
    <section id="karya-terbaru" class="py-24 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-text mb-2">Karya Terbaru</h2>
                    <p class="text-text/90 font-medium leading-relaxed">Beberapa mahakarya terbaru yang telah kami selesaikan.</p>
                </div>
                <a href="{{ route('projects.index') }}" class="hidden md:inline-flex items-center text-primary hover:text-accent font-medium transition-colors">
                    Lihat Semua 
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestProjects as $project)
                    <div class="bg-white border border-secondary/30 rounded-lg overflow-hidden hover:border-accent/50 transition-all duration-300 group shadow-sm hover:shadow-md">
                        <div class="aspect-w-16 aspect-h-10 bg-secondary/10 relative">
                            @if($project->media && $project->media->count() > 0)
                                <img src="{{ asset('storage/' . $project->media->first()->file_path) }}" alt="{{ $project->title }}" class="object-cover w-full h-64 group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="flex items-center justify-center w-full h-64 text-text/40">
                                    Tidak ada gambar
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-text mb-2 group-hover:text-primary transition-colors">{{ $project->title }}</h3>
                            <p class="text-text/90 font-medium text-base leading-relaxed mb-4 line-clamp-2">{{ strip_tags($project->description) }}</p>
                            <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors">
                                Selengkapnya 
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-text/70 font-medium">Belum ada karya yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Blog Terbaru Section --}}
    <section class="py-24 bg-secondary/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-text mb-2">Artikel Terbaru</h2>
                    <p class="text-text/90 font-medium leading-relaxed">Inspirasi dan cerita seputar dunia ukir kayu.</p>
                </div>
                <a href="{{ route('blog.index') }}" class="hidden md:inline-flex items-center text-primary hover:text-accent font-medium transition-colors">
                    Lihat Semua Artikel
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestBlogs as $blog)
                    <a href="{{ route('blog.show', $blog->slug) }}" class="group block">
                        <div class="mb-4 overflow-hidden rounded-lg bg-white border border-secondary/30 shadow-sm">
                            @if($blog->thumbnail_path)
                                <img src="{{ asset('storage/' . $blog->thumbnail_path) }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-48 bg-secondary/20 flex items-center justify-center text-text/40">Tanpa Thumbnail</div>
                            @endif
                        </div>
                        <div class="flex items-center text-xs text-text/80 font-medium mb-2 gap-2">
                            <span>{{ $blog->created_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span>{{ $blog->user->name ?? 'Admin' }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-text group-hover:text-primary transition-colors line-clamp-2">{{ $blog->title }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
