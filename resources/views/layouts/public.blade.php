<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ukir') - Blog</title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --text: #333;
            --bg: #f8f9fa;
            --white: #fff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }
        .navbar { background: var(--white); box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 1.5rem; font-weight: bold; color: var(--primary); text-decoration: none; }
        .navbar-menu { display: flex; gap: 1.5rem; align-items: center; }
        .navbar-menu a { color: var(--text); text-decoration: none; font-weight: 500; }
        .navbar-menu a:hover { color: var(--accent); }
        .btn-primary { background: var(--accent); color: var(--white); padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; }
        .btn-primary:hover { background: #2980b9; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .hero { background: linear-gradient(135deg, var(--primary), var(--accent)); color: var(--white); padding: 4rem 2rem; text-align: center; margin-bottom: 2rem; }
        .hero h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        .hero p { font-size: 1.2rem; opacity: 0.9; }
        .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
        .blog-card { background: var(--white); border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .blog-card:hover { transform: translateY(-4px); }
        .blog-card-img { width: 100%; height: 200px; object-fit: cover; background: #ddd; }
        .blog-card-body { padding: 1.5rem; }
        .blog-card-title { font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--primary); }
        .blog-card-meta { font-size: 0.85rem; color: #666; margin-bottom: 1rem; }
        .blog-card-excerpt { color: #555; margin-bottom: 1rem; }
        .blog-card-link { color: var(--accent); text-decoration: none; font-weight: 500; }
        .blog-single { background: var(--white); border-radius: 8px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .blog-single-header { margin-bottom: 2rem; border-bottom: 2px solid var(--bg); padding-bottom: 1rem; }
        .blog-single-title { font-size: 2rem; color: var(--primary); margin-bottom: 0.5rem; }
        .blog-single-meta { color: #666; font-size: 0.9rem; }
        .blog-single-content { font-size: 1.1rem; line-height: 1.8; }
        .blog-single-content p { margin-bottom: 1.5rem; }
        .blog-single-img { max-width: 100%; border-radius: 8px; margin: 1.5rem 0; }
        .related-posts { margin-top: 3rem; }
        .related-posts h3 { margin-bottom: 1rem; color: var(--primary); }
        .footer { background: var(--primary); color: var(--white); text-align: center; padding: 2rem; margin-top: 3rem; }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; }
        .pagination a, .pagination span { padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: var(--text); }
        .pagination .active { background: var(--accent); color: var(--white); border-color: var(--accent); }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">UKIR</a>
        <div class="navbar-menu">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            @auth
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--text);font:inherit;">Logout</button>
                </form>
            @else
                <a href="{{ route('login.show') }}" class="btn-primary">Login</a>
            @endauth
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Ukir. All rights reserved.</p>
    </footer>
</body>
</html>