<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Platform blog modern untuk berbagi cerita dan inspirasi.')">
    <title>@yield('title', 'Ukir') - Blog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg-light text-text-dark font-sans antialiased min-h-screen flex flex-col selection:bg-accent selection:text-primary">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-border shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-text-light font-bold text-sm group-hover:bg-accent transition-colors">U</div>
                    <span class="text-xl font-bold text-primary tracking-tight group-hover:text-accent transition-colors">UKIR</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-text-dark hover:text-primary transition-colors">Beranda</a>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium text-text-dark hover:text-primary transition-colors">Blog</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-text-dark hover:text-primary transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-text-light transition-all duration-200">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login.show') }}" class="px-4 py-2 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-text-light transition-all duration-200">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-primary text-text-light/90 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-text-light mb-3">UKIR</h3>
                    <p class="text-sm text-text-light/70 leading-relaxed">Platform blog modern untuk berbagi cerita, ide, dan inspirasi dengan sentuhan estetika yang timeless.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-text-light mb-3">Tautan</h4>
                    <ul class="space-y-2 text-sm text-text-light/70">
                        <li><a href="{{ route('home') }}" class="hover:text-accent transition-colors">Beranda</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-accent transition-colors">Semua Artikel</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-text-light mb-3">Kontak</h4>
                    <p class="text-sm text-text-light/70">hello@ukir.blog</p>
                </div>
            </div>
            <div class="border-t border-text-light/20 mt-8 pt-6 text-center text-xs text-text-light/50">
                &copy; {{ date('Y') }} Ukir. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>