@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('breadcrumb', 'Lupa Password')

@section('content')
    <p class="text-text/80 text-sm text-center mb-6">
        Masukkan email akun Anda. Jika terdaftar, kode verifikasi 6 digit akan dikirim ke email tersebut.
    </p>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
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
        </div>

        <button type="submit" class="w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent transition-colors">
            Kirim Kode Verifikasi
        </button>
    </form>

    <div class="text-center mt-6 pt-6 border-t border-secondary/30 text-text/80 text-sm">
        <a href="{{ route('password.verify') }}" class="text-accent font-medium hover:underline">Sudah punya kode?</a>
        <span class="mx-2">/</span>
        <a href="{{ route('login.show') }}" class="text-accent font-medium hover:underline">Kembali ke login</a>
    </div>
@endsection
