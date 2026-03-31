@extends('layouts.auth')
@section('title', 'Register')
@section('breadcrumb', 'Register')
@section('content')
<form action="{{ route('register') }}" method="POST">
    @csrf
    
    <!-- Name -->
    <div class="form-group">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-input" 
            value="{{ old('name') }}" 
            required 
            maxlength="255"
            placeholder="Nama Anda"
        >
        @error('name')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

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
            placeholder="nama@example.com"
        >
        @error('email')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone (Optional) -->
    <div class="form-group">
        <label for="phone" class="form-label">Nomor Telepon <small style="color: var(--text-muted);">(Opsional)</small></label>
        <input 
            type="text" 
            id="phone" 
            name="phone" 
            class="form-input" 
            value="{{ old('phone') }}" 
            maxlength="20"
            placeholder="08123456789"
        >
        @error('phone')
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
            minlength="8"
            placeholder="••••••••"
        >
        @error('password')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Confirmation -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            class="form-input" 
            required 
            placeholder="••••••••"
        >
    </div>

    <!-- Submit -->
    <button type="submit" class="btn-primary">Daftar</button>
</form>

<!-- Link to Login -->
<div class="auth-footer">
    <span style="color: var(--text-muted);">Sudah punya akun? </span>
    <a href="{{ route('login.show') }}">Masuk sekarang</a>
</div>
@endsection