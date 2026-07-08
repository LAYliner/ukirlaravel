@extends('layouts.main')

@section('title', 'Kontak - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-bold text-text mb-4">Hubungi Kami</h1>
        <p class="text-xl text-text/90 font-medium leading-relaxed max-w-3xl">Tertarik dengan karya ukir kami? Jangan ragu untuk menghubungi kami melalui berbagai saluran di bawah ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- WhatsApp --}}
        <div class="bg-white border border-secondary/30 rounded-lg p-8 hover:border-accent/50 transition-all duration-300 shadow-sm hover:shadow-md group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.437-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text">WhatsApp</h3>
            </div>
            <p class="text-text/90 font-medium mb-4">Hubungi kami langsung melalui WhatsApp untuk pertanyaan dan pemesanan.</p>
            <a href="https://wa.me/6282136605245" target="_blank" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors">
                Whatasapp Sanggar Ukir Tana Paser
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- Email --}}
        <div class="bg-white border border-secondary/30 rounded-lg p-8 hover:border-accent/50 transition-all duration-300 shadow-sm hover:shadow-md group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text">Email</h3>
            </div>
            <p class="text-text/90 font-medium mb-4">Kirimkan email kepada kami untuk informasi lebih lanjut.</p>
            <a href="mailto:sanggarukirtanapaser@gmail.com" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors">
            sanggarukirtanapaser@gmail.com
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- Facebook Page --}}
        <div class="bg-white border border-secondary/30 rounded-lg p-8 hover:border-accent/50 transition-all duration-300 shadow-sm hover:shadow-md group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text">Facebook Page</h3>
            </div>
            <p class="text-text/90 font-medium mb-4">Ikuti kami di Facebook untuk update terbaru dan inspirasi.</p>
            <a href="https://facebook.com/sanggar.u.larasati" target="_blank" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors">
                Sanggar Ukir Tana Paser
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- TikTok --}}
        <div class="bg-white border border-secondary/30 rounded-lg p-8 hover:border-accent/50 transition-all duration-300 shadow-sm hover:shadow-md group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center group-hover:bg-gray-800 transition-colors">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.51.41-.9.01-1.91 0-3.83 0-5.75 0-1.75-.01-3.5.01-5.25-.01-1.45.01-2.9.02-4.35z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-text">TikTok</h3>
            </div>
            <p class="text-text/90 font-medium mb-4">Tonton video proses pembuatan dan hasil karya ukir kami.</p>
            <a href="https://tiktok.com/@sanggarukirtanapaser" target="_blank" class="inline-flex items-center text-base font-medium text-primary hover:text-accent transition-colors">
                @sanggarukirtanapaser
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    {{-- Location Section --}}
    <div class="mt-12 bg-white border border-secondary/30 rounded-lg p-8 shadow-sm">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
    </div>
</div>
@endsection