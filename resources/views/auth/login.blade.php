@extends('layouts.auth')

@section('title', 'Login')
@section('breadcrumb', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block mb-2 font-medium text-text">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
                value="{{ old('email') }}" 
                required 
                autofocus
                placeholder="nama@example.com"
            >
            @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block mb-2 font-medium text-text">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white" 
                required 
                placeholder="••••••••"
            >
            @error('password')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent transition-colors">Masuk</button>
    </form>

    <!-- Link ke Register -->
    <div class="text-center mt-6 pt-6 border-t border-secondary/30 text-text/80">
        <span>Belum punya akun? </span>
        <a href="{{ route('register.show') }}" class="text-accent font-medium hover:underline">Daftar sekarang</a>
    </div>
@endsection