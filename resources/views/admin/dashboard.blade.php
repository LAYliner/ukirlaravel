@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h2>📝 Manajemen Blog</h2>
        <p>Kelola artikel dan konten blog dari sini.</p>
        <br>
        <div style="display: flex; gap: 1rem;">
            <button style="padding: 0.5rem 1rem; background: var(--accent-color); color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                + Buat Artikel Baru
            </button>
            <button style="padding: 0.5rem 1rem; background: #fff; color: var(--text-dark); border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer;">
                Lihat Semua Artikel
            </button>
        </div>
    </div>

    <div class="card">
        <h2>📈 Statistik Singkat</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3>0</h3>
                <small>Total Artikel</small>
            </div>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3>0</h3>
                <small>Total User</small>
            </div>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; text-align: center;">
                <h3>0</h3>
                <small>Komentar</small>
            </div>
        </div>
    </div>
@endsection
<p>Role: {{ auth()->user()->role }}</p>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>