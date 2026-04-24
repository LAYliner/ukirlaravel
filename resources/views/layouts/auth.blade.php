<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- ⚠️ PENTING: Prevent Cache untuk halaman auth -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <title>@yield('title', 'Autentikasi') - Sanggar Ukir Tana Paser</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-background text-text font-sans antialiased min-h-screen flex flex-col selection:bg-accent selection:text-background">
    <!-- Breadcrumb -->
    <header class="p-4 md:p-8 w-full max-w-7xl mx-auto">
        <nav class="text-sm text-text/60 font-medium flex items-center gap-2">
            <a href="{{ route('home') }}" class="text-primary hover:underline">Beranda</a>
            <span>/</span>
            <span class="text-text/80">@yield('breadcrumb', 'Login')</span>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-sm border border-secondary/30 p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold text-primary mb-6 text-center">@yield('title', 'Login')</h1>
            @yield('content')
        </div>
    </main>

    @stack('scripts')
    
    <!-- PENTING: JavaScript Detect Back Navigation -->
    <script>
        // Reload page jika navigasi via back/forward button (BFCache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
        
        // Alternatif: detect navigation type
        if (window.performance && window.performance.getEntriesByType) {
            const navEntries = window.performance.getEntriesByType('navigation');
            if (navEntries.length > 0 && navEntries[0].type === 'back_forward') {
                window.location.reload();
            }
        }
    </script>
</body>
</html>