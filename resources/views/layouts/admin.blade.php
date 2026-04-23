<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Ukir</title>
    
    <!-- CSS Internal untuk Sidebar & Responsive -->
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 60px;
            --primary-color: #885007;
            --accent-color: #dda45a;
            --text-light: #ecf0f1;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            overflow-x: hidden;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-color);
            color: var(--text-light);
            transition: transform var(--transition-speed) ease;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            transform: translateX(calc(-1 * var(--sidebar-width)));
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-light);
            text-decoration: none;
            white-space: nowrap;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.1);
            color: var(--text-light);
            border-left: 4px solid var(--accent-color);
        }

        .sidebar-nav a .icon {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            min-width: 24px;
        }

        .sidebar-user {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
        }

        .sidebar-user-info {
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left var(--transition-speed) ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* ==================== HEADER ==================== */
        .top-header {
            height: var(--header-height);
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-color);
            padding: 0.5rem;
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        .toggle-btn:hover {
            background: #f0f0f0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.2s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* ==================== CONTENT AREA ==================== */
        .content-area {
            padding: 1.5rem;
        }

        /* ==================== ALERTS ==================== */
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* ==================== RESPONSIVE ==================== */
        /* Tablet & Mobile (max-width: 992px) */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 998;
            }

            .overlay.active {
                display: block;
            }
        }

        /* Mobile Small (max-width: 576px) */
        @media (max-width: 576px) {
            .top-header {
                padding: 0 1rem;
            }

            .content-area {
                padding: 1rem;
            }

            .sidebar-brand {
                font-size: 1.2rem;
            }
        }

        /* Hide scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        /* Fix Pagination SVG Size */
        .pagination svg {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
        }

        /* Optional: Style pagination container */
        .pagination {
            margin-top: 1rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #3498db;
            display: inline-block;
        }

        .pagination a:hover {
            background: #f8f9fa;
        }

        .pagination .active {
            background: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Overlay untuk Mobile -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">UKIR ADMIN</a>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.blog.index') ?? '#' }}" class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <span class="icon">📝</span>
                <span>Blog</span>
            </a>
            <a href="{{ route('admin.projects.index') ?? '#' }}" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
               <span>Projects</span>
            </a>
            <a href="{{ route('admin.categories.index') ?? '#' }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="icon">📁</span>
                <span>Kategori</span>
            </a>
            <a href="#" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="icon">👤</span>
                <span>Users</span>
            </a>
            <a href="{{ route('blog.index') }}" target="_blank">
                <span class="icon">🔗</span>
                <span>Lihat Public</span>
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <strong>{{ auth()->user()->name }}</strong>
            </div>
            <div class="sidebar-user-role">Role: {{ auth()->user()->role }}</div>
            <form action="{{ route('logout') }}" method="POST" style="margin-top: 0.5rem;">
                @csrf
                <button type="submit" class="logout-btn" style="width: 100%; text-align: left;">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Header -->
        <header class="top-header">
            <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
                ☰
            </button>
            <div class="header-actions">
                <span>{{ auth()->user()->name }}</span>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif -->

            @yield('content')
        </div>
    </div>

    <!-- JavaScript untuk Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Check screen size
            const isMobile = () => window.innerWidth <= 992;
            
            // Toggle sidebar
            toggleBtn.addEventListener('click', function() {
                if (isMobile()) {
                    // Mobile behavior
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('active');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    
                    // Save state to localStorage
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                }
            });
            
            // Close sidebar when clicking overlay (mobile)
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            });
            
            // Restore sidebar state on load (desktop only)
            if (!isMobile() && localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>