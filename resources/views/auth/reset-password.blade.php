@extends('layouts.auth')

@section('title', 'Reset Password')
@section('breadcrumb', 'Reset Password')

@section('content')
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="mb-5">
            <label for="password" class="block mb-2 font-medium text-text">Password Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
            >
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 font-medium text-text">Konfirmasi Password Baru</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Ulangi password baru"
            >
        </div>

        <button type="submit" class="w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent transition-colors">
            Simpan Password Baru
        </button>
    </form>
@endsection
