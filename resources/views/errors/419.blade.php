<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - Ukir</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
        .icon { font-size: 3rem; margin-bottom: 1rem; }
        h1 { color: #2c3e50; margin-bottom: 0.5rem; }
        p { color: #666; margin-bottom: 1.5rem; }
        .btn { background: #27ae60; color: #fff; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; text-decoration: none; display: inline-block; }
        .btn:hover { background: #219a52; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">⏰</div>
        <h1>Session Expired</h1>
        <p>Halaman ini telah kedaluwarsa. Silakan login kembali untuk melanjutkan.</p>
        <a href="{{ route('login.show') }}" class="btn">Kembali ke Login</a>
    </div>
</body>
</html>