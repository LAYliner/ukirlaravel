@extends('layouts.auth')

@section('title', 'Verifikasi Berhasil')
@section('breadcrumb', 'Verifikasi Sukses')

@section('content')
<div class="text-center py-6">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-6">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    
    <h2 class="text-2xl font-bold text-text mb-2">Akun Terverifikasi!</h2>
    <p class="text-text/60 mb-8">
        Selamat, email Anda telah berhasil diverifikasi. Akun Anda sekarang aktif dan dapat digunakan untuk masuk ke sistem.
    </p>

    <a 
        href="{{ route('login.show') }}" 
        class="block w-full py-3 bg-primary text-background font-medium rounded-lg hover:bg-accent text-center transition-colors"
    >
        Masuk ke Akun Anda
    </a>
</div>
@endsection
