<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sanggar Ukir Tana Paser - Seni Ukir Tradisional</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        .bg-hero {
            background-image: linear-gradient(to bottom right, var(--color-secondary), var(--color-secondary));
        }
    </style>
</head>
<body class="bg-background text-text font-sans antialiased min-h-screen flex flex-col selection:bg-accent selection:text-background">

    {{-- Navigation --}}
    <nav class="fixed w-full z-50 bg-background/90 backdrop-blur-md border-b border-secondary/30 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl font-bold text-primary tracking-tight group-hover:text-accent transition-colors uppercase">Sanggar Ukir Tana Paser</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-base font-medium text-primary transition-colors">Beranda</a>
                    <a href="{{ route('projects.index') }}" class="text-base font-medium text-text/90 hover:text-primary transition-colors">Karya</a>
                    <a href="{{ route('blog.index') }}" class="text-base font-medium text-text/90 hover:text-primary transition-colors">Blog</a>
                    <a href="#" class="text-base font-medium text-text/90 hover:text-primary transition-colors">Tentang</a>
                    <a href="#" class="text-base font-medium text-text/90 hover:text-primary transition-colors">Kontak</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200">Dashboard</a>
                    @else
                        <a href="{{ route('login.show') }}" class="px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-grow">
        
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

    </main>

    {{-- Footer --}}
    <footer class="bg-background border-t border-secondary/30 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <span class="text-2xl font-bold text-primary tracking-tight uppercase mb-4 block">Sanggar Ukir Tana Paser</span>
                    <p class="text-text/90 font-medium max-w-sm leading-relaxed">
                        Melestarikan warisan budaya melalui karya seni ukir yang autentik dan bernilai tinggi.
                    </p>
                </div>
                <div>
                    <h4 class="text-text font-semibold mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-text/80 font-medium hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="{{ route('projects.index') }}" class="text-text/80 font-medium hover:text-primary transition-colors">Karya</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-text/80 font-medium hover:text-primary transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-text font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-text/80 font-medium">
                        <li>Jepara, Jawa Tengah</li>
                        <li>hello@ekaukirmas.com</li>
                        <li>+62 812 3456 7890</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-secondary/30 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-text/80 font-medium text-base">
                    &copy; {{ date('Y') }} Sanggar Ukir Tana Paser. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
