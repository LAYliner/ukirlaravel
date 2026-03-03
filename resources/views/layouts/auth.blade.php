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
    
    <title>@yield('title', 'Autentikasi') - Ukir</title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #27ae60;
            --bg: #f4f6f9;
            --white: #fff;
            --text: #333;
            --text-muted: #666;
            --border: #ddd;
            --error: #e74c3c;
            --shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header dengan Tree Icon */
        .auth-header {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .breadcrumb {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            margin: 0 0.5rem;
            color: var(--text-muted);
        }

        /* Main Content */
        .auth-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .form-error {
            color: var(--error);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #219a52;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Breadcrumb -->
    <header class="auth-header">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>→</span>
            <span>@yield('breadcrumb', 'Login')</span>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="auth-main">
        <div class="auth-card">
            <h1 class="auth-title">@yield('title', 'Login')</h1>
            @yield('content')
        </div>
    </main>

    @stack('scripts')
    
    <!--  PENTING: JavaScript Detect Back Navigation -->
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