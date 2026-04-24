<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sanggar Ukir Tana Paser')</title>
    <meta name="description" content="@yield('meta_description', 'Seni Ukir Tradisional Berkualitas Tinggi dari Jepara')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    @stack('styles')
</head>
<body class="bg-background text-text font-sans antialiased min-h-screen flex flex-col selection:bg-accent selection:text-background">

    {{-- Navigation --}}
    <nav class="fixed w-full z-50 bg-background/95 backdrop-blur-md border-b border-secondary/30 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl font-bold text-primary tracking-tight group-hover:text-accent transition-colors uppercase">Sanggar Ukir Tana Paser</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-base font-medium {{ request()->routeIs('home') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Beranda</a>
                    <a href="{{ route('projects.index') }}" class="text-base font-medium {{ request()->routeIs('projects.*') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Karya</a>
                    <a href="{{ route('blog.index') }}" class="text-base font-medium {{ request()->routeIs('blog.*') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Blog</a>
                    <a href="#" class="text-base font-medium text-text/90 hover:text-primary transition-colors">Tentang</a>
                    <a href="#" class="text-base font-medium text-text/90 hover:text-primary transition-colors">Kontak</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-base font-medium text-text/90 hover:text-primary transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login.show') }}" class="px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-background border-t border-secondary/30 pt-16 pb-8 mt-20">
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
            <div class="border-t border-secondary/30 pt-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-text/80 font-medium text-base">
                    &copy; {{ date('Y') }} Sanggar Ukir Tana Paser. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
