<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Prevent Cache untuk halaman admin -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Admin Dashboard') - Ukir</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            UKIR ADMIN
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.blog.index') ?? '#' }}" class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                    📝 Blog
                </a>
            </li>
            <li>
                <a href="#">
                    👤 Users
                </a>
            </li>
            <li>
                <a href="#">
                    ⚙️ Settings
                </a>
            </li>
            <li>
                <a href="{{ route('blog.index') }}" target="_blank">
                    🔗 Lihat Public Blog
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <small>Role: {{ auth()->user()->role }}</small>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="top-header">
            <div class="user-info">
                <span>Halo, <strong>{{ auth()->user()->name }}</strong></span>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content-area">
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>