@extends('layouts.auth')

@section('title', 'Verifikasi Kode')
@section('breadcrumb', 'Verifikasi Kode')

@section('content')
    <p class="text-text/80 text-sm text-center mb-6">
        Masukkan email dan kode verifikasi 6 digit yang dikirim untuk reset password.
    </p>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.verify.post') }}" class="mb-6">
        @csrf

        <div class="mb-5">
            <label for="email" class="block mb-2 font-medium text-text">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="w-full p-3 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all bg-white"
                value="{{ old('email', $email) }}"
                required
                placeholder="nama@example.com"
            >
        </div>

        <div class="mb-6">
            <label for="token" class="block mb-2 font-medium text-text text-center">Kode Verifikasi</label>
            <input
                type="text"
                id="token"
                name="token"
                maxlength="6"
                pattern="[0-9]{6}"
                inputmode="numeric"
                class="w-full p-4 border border-secondary/30 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent text-center text-3xl font-bold tracking-[0.5em] bg-white"
                required
                autofocus
                placeholder="000000"
            >
        </div>

        <button type="submit" class="w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent transition-colors">
            Verifikasi Kode
        </button>
    </form>

    <form method="POST" action="{{ route('password.resend') }}" class="text-center mt-6 pt-6 border-t border-secondary/30 text-text/80 text-sm">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email) }}">
        <span>Tidak menerima kode? </span>
        <button type="submit" class="text-accent font-medium hover:underline focus:outline-none">
            Kirim ulang
        </button>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tokenInput = document.getElementById('token');

                tokenInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 6);
                });
            });
        </script>
    @endpush
@endsection
