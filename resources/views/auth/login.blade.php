@extends('layouts.auth')

@section('title', 'Login')
@section('breadcrumb', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input" 
                value="{{ old('email') }}" 
                required 
                autofocus
                placeholder="nama@example.com"
            >
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input" 
                required 
                placeholder="••••••••"
            >
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">Masuk</button>
    </form>

    <!-- Link ke Register -->
    <div class="auth-footer">
        <span style="color: var(--text-muted);">Belum punya akun? </span>
        <a href="{{ route('register.show') }}">Daftar sekarang</a>
    </div>
@endsection