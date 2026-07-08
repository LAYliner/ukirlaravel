<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <img src="{{ asset('images/logo_sanggar_ukir.png') }}" alt="Logo Sanggar Ukir Tana Paser" class="h-10 w-auto object-contain">
                    <span class="text-lg sm:text-xl md:text-2xl font-bold text-primary tracking-tight group-hover:text-accent transition-colors uppercase truncate max-w-[200px] sm:max-w-none">Sanggar Ukir Tana Paser</span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-base font-medium {{ request()->routeIs('home') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Beranda</a>
                    <a href="{{ route('projects.index') }}" class="text-base font-medium {{ request()->routeIs('projects.*') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Proyek</a>
                    <a href="{{ route('blog.index') }}" class="text-base font-medium {{ request()->routeIs('blog.*') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Blog</a>
                    <a href="{{ route('about') }}" class="text-base font-medium {{ request()->routeIs('about') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Tentang</a>
                    <a href="{{ route('contact') }}" class="text-base font-medium {{ request()->routeIs('contact') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Kontak</a>
                </div>

                {{-- Desktop Auth Buttons --}}
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 group mr-2">
                            <img src="{{ Auth::user()->profile_picture_url }}" class="w-8 h-8 rounded-full object-cover border border-secondary/50 group-hover:border-primary transition-colors" alt="Avatar">
                            <span class="text-base font-medium text-text/90 group-hover:text-primary transition-colors">{{ Auth::user()->name }}</span>
                        </a>
                        @if(Auth::user()->role !== 'user')
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-base font-medium text-text/90 hover:text-primary transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login.show') }}" class="px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200">Masuk</a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-md text-text hover:text-primary focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Panel --}}
        <div id="mobile-menu" class="hidden md:hidden bg-background border-t border-secondary/30">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" class="block text-base font-medium {{ request()->routeIs('home') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Beranda</a>
                <a href="{{ route('projects.index') }}" class="block text-base font-medium {{ request()->routeIs('projects.*') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Proyek</a>
                <a href="{{ route('blog.index') }}" class="block text-base font-medium {{ request()->routeIs('blog.*') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Blog</a>
                <a href="{{ route('about') }}" class="text-base font-medium {{ request()->routeIs('about') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Tentang</a>
                <a href="{{ route('contact') }}" class="text-base font-medium {{ request()->routeIs('contact') ? 'text-primary' : 'text-text/90 hover:text-primary' }} transition-colors">Kontak</a>

                <div class="border-t border-secondary/30 pt-4 mt-4 space-y-3">
                    @auth
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 group">
                            <img src="{{ Auth::user()->profile_picture_url }}" class="w-8 h-8 rounded-full object-cover border border-secondary/50" alt="Avatar">
                            <span class="text-base font-medium text-text/90">{{ Auth::user()->name }}</span>
                        </a>
                        @if(Auth::user()->role !== 'user')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200 text-center">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 text-base font-medium text-text/90 hover:text-primary transition-colors text-left">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login.show') }}" class="block px-4 py-2 text-base font-medium text-primary border border-primary rounded hover:bg-primary hover:text-background transition-all duration-200 text-center">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

    {{-- Main Content --}}
    <main class="flex-grow {{ request()->routeIs('home') ? '' : 'pt-20' }}">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-background border-t border-secondary/30 pt-16 pb-8 {{ request()->routeIs('home') ? '' : 'mt-20' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo_sanggar_ukir.png') }}" alt="Logo Sanggar Ukir Tana Paser" class="h-12 w-auto object-contain">
                        <span class="text-2xl font-bold text-primary tracking-tight uppercase">Sanggar Ukir Tana Paser</span>
                    </div>
                    <p class="text-text/90 font-medium max-w-sm leading-relaxed">
                        Melestarikan warisan budaya melalui karya seni ukir yang autentik dan bernilai tinggi.
                    </p>
                </div>
                <div>
                    <h4 class="text-text font-semibold mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-text/80 font-medium hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="{{ route('projects.index') }}" class="text-text/80 font-medium hover:text-primary transition-colors">Proyek</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-text/80 font-medium hover:text-primary transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-text font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-text/80 font-medium">
                        <li>Tanah Grogot, Kabupaten Paser, Kalimantan Timur</li>
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

    {{-- Tag Selector Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-tag-selector]').forEach(function (root) {
            const trigger   = root.querySelector('[data-tag-trigger]');
            const panel     = root.querySelector('[data-tag-panel]');
            const label     = root.querySelector('[data-tag-label]');
            const chevron   = root.querySelector('[data-tag-chevron]');
            const search    = root.querySelector('[data-tag-search]');
            const items     = root.querySelectorAll('[data-tag-item]');
            const checkboxes= root.querySelectorAll('[data-tag-checkbox]');
            const clearBtn  = root.querySelector('[data-tag-clear]');
            const applyBtn  = root.querySelector('[data-tag-apply]');
            const emptyMsg  = root.querySelector('[data-tag-empty]');

            function updateLabel() {
                const checked = Array.from(checkboxes).filter(cb => cb.checked);
                if (checked.length === 0) {
                    // Untuk radio button (kategori), tampilkan "Semua Kategori" jika tidak ada yang dipilih
                    const radioChecked = Array.from(checkboxes).find(cb => cb.type === 'radio' && cb.checked);
                    if (radioChecked) {
                        label.textContent = radioChecked.dataset.tagNameLabel;
                    } else {
                        label.textContent = 'Pilih tag';
                    }
                } else if (checked[0].type === 'radio') {
                    // Untuk radio button, ambil yang checked
                    const radioChecked = Array.from(checkboxes).find(cb => cb.checked);
                    label.textContent = radioChecked ? radioChecked.dataset.tagNameLabel : 'Pilih tag';
                } else if (checked.length <= 2) {
                    label.textContent = checked.map(cb => cb.dataset.tagNameLabel).join(', ');
                } else {
                    label.textContent = `${checked.length} tag dipilih`;
                }
            }

            function togglePanel(open) {
                const willOpen = open !== undefined ? open : panel.classList.contains('hidden');
                panel.classList.toggle('hidden', !willOpen);
                chevron.classList.toggle('rotate-180', willOpen);
            }

            trigger.addEventListener('click', () => togglePanel());

            document.addEventListener('click', (e) => {
                if (!root.contains(e.target)) togglePanel(false);
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateLabel));

            if (search) {
                search.addEventListener('input', () => {
                    const q = search.value.trim().toLowerCase();
                    let anyVisible = false;
                    items.forEach(item => {
                        const match = item.dataset.tagName.includes(q);
                        item.classList.toggle('hidden', !match);
                        if (match) anyVisible = true;
                    });
                    emptyMsg.classList.toggle('hidden', anyVisible);
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    checkboxes.forEach(cb => {
                        if (cb.type === 'radio') {
                            // Untuk radio, pilih yang pertama (Semua Kategori)
                            if (cb.value === '') cb.checked = true;
                        } else {
                            cb.checked = false;
                        }
                    });
                    updateLabel();
                });
            }

            if (applyBtn) {
                applyBtn.addEventListener('click', () => togglePanel(false));
            }

            updateLabel();
        });
    });
    </script>
</body>
</html>