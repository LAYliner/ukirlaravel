@extends('layouts.auth')
@section('title', 'Register')
@section('breadcrumb', 'Register')
@section('content')
<form action="{{ route('register') }}" method="POST">
    @csrf
    
    <!-- Name -->
    <div class="mb-4">
        <label for="name" class="block mb-2 font-medium text-text">Nama Lengkap</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
            value="{{ old('name') }}" 
            required 
            maxlength="255"
            placeholder="Nama Anda"
        >
        @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-4">
        <label for="email" class="block mb-2 font-medium text-text">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
            value="{{ old('email') }}" 
            required 
            placeholder="nama@example.com"
        >
        @error('email')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone (Optional) -->
    <div class="mb-4">
        <label for="phone" class="block mb-2 font-medium text-text">Nomor Telepon <small class="text-text/60 font-normal">(Opsional)</small></label>
        <input 
            type="text" 
            id="phone" 
            name="phone" 
            class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
            value="{{ old('phone') }}" 
            maxlength="20"
            placeholder="08123456789"
        >
        @error('phone')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-4">
        <label for="password" class="block mb-2 font-medium text-text">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
            required 
            minlength="8"
            placeholder="••••••••"
        >
        @error('password')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Confirmation -->
    <div class="mb-6">
        <label for="password_confirmation" class="block mb-2 font-medium text-text">Konfirmasi Password</label>
        <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
            required 
            placeholder="••••••••"
        >
    </div>

    <!-- Submit -->
    <button type="submit" class="w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent transition-colors">Daftar</button>
</form>

<!-- Link to Login -->
<div class="text-center mt-6 pt-6 border-t border-secondary/30 text-text/80">
    <span>Sudah punya akun? </span>
    <a href="{{ route('login.show') }}" class="text-accent font-medium hover:underline">Masuk sekarang</a>
</div>
@endsection