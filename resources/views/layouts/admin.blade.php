<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Ukir</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        /* Fallback transition jika Tailwind belum load sempurna */
        .sidebar-transition {
            transition: transform 0.2s ease-in-out;
        }
    </style>
</head>
<body class="bg-background text-text font-sans antialiased h-full overflow-hidden">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <!-- ID 'sidebar' digunakan untuk target JS -->
        <aside id="sidebar" 
               class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-secondary/30 transform -translate-x-full md:translate-x-0 sidebar-transition flex flex-col shadow-lg md:shadow-none">
            
            <!-- Logo / Header Sidebar -->
            <div class="py-4 flex items-center px-6 border-b border-secondary/30 shrink-0 flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-primary tracking-tight">
                    UKIR ADMIN
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <!-- Blog -->
                <a href="{{ route('admin.blog.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.blog.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.blog.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Blog
                </a>

                <!-- Projects -->
                <a href="{{ route('admin.projects.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.projects.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.projects.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Proyek
                </a>

                <!-- Categories -->
                <a href="{{ route('admin.categories.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Kategori
                </a>
                
                <!-- Tags -->
                <a href="{{ route('admin.tags.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.tags.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.tags.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M15 7h.01M11 7h.01M7 11h.01M15 11h.01M11 11h.01M7 15h.01M15 15h.01M11 15h.01" />
                    </svg>
                    Tags
                </a>

                <!-- Comments (Admin Only) -->
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.comments.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.comments.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.comments.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Komentar
                </a>

                <!-- Users (Admin Only) -->
                <a href="{{ route('admin.users.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    User
                </a>
                @endif
                
                <!-- Public Site Link -->
                <a href="{{ route('home') }}" target="_blank"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:bg-gray-50 hover:text-gray-900 mt-4 pt-4 border-t border-gray-200">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Lihat Public
                </a>
            </nav>

            <!-- User Profile / Logout -->
            <div class="border-t border-secondary/30 p-4 shrink-0">
                <a href="{{ route('profile.show') }}" class="flex items-center p-2 -m-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex flex-col flex-1 md:ml-64 min-h-screen overflow-hidden">
            
            <!-- Top Header -->
            <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-secondary/30 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <!-- Mobile Menu Button -->
                    <!-- ID 'mobile-menu-button' digunakan untuk trigger JS -->
                    <button type="button" 
                            id="mobile-menu-button"
                            class="md:hidden mr-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary p-1 rounded-md"
                            aria-label="Toggle sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- Placeholder -->
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Vanilla JS for Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (sidebar && mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    // Toggle class translate-x-full
                    if (sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('translate-x-0');
                    } else {
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                    }
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnButton = mobileMenuButton.contains(event.target);
                    
                    // Hanya tutup jika layar kecil (md breakpoint check via window width atau class md:hidden)
                    // Sederhananya: jika sidebar terbuka dan klik di luar sidebar & bukan tombol
                    if (!isClickInsideSidebar && !isClickOnButton && window.innerWidth < 768) {
                         if (!sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.add('-translate-x-full');
                            sidebar.classList.remove('translate-x-0');
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>