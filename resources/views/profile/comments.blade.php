@extends('layouts.main')

@section('title', 'Riwayat Komentar - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Breadcrumb / Back Navigation --}}
    <div class="mb-8">
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-accent transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Pengaturan Profil
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-4xl font-bold text-text mb-2">Riwayat Komentar Anda</h1>
        <p class="text-text/80 font-medium font-sans">Berikut adalah daftar semua komentar yang pernah Anda tulis di artikel blog dan karya kami.</p>
    </div>

    @if($comments->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white border border-secondary/30 rounded-2xl p-12 text-center max-w-2xl mx-auto shadow-sm">
            <div class="w-20 h-20 bg-secondary/15 rounded-full flex items-center justify-center mx-auto mb-6 text-accent">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-text mb-2">Belum Ada Komentar</h3>
            <p class="text-text/75 font-medium mb-8">Anda belum pernah meninggalkan komentar pada konten apa pun. Mulailah berinteraksi dengan membaca artikel atau melihat karya kami.</p>
            <div class="flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('blog.index') }}" class="px-6 py-2.5 bg-primary text-background font-semibold rounded-lg hover:bg-primary/95 transition-all text-sm shadow-sm hover:shadow">
                    Kunjungi Blog
                </a>
                <a href="{{ route('projects.index') }}" class="px-6 py-2.5 bg-secondary/25 text-primary border border-secondary/60 font-semibold rounded-lg hover:bg-secondary/40 transition-all text-sm">
                    Lihat Portofolio Karya
                </a>
            </div>
        </div>
    @else
        {{-- Comment List --}}
        <div class="space-y-6">
            @foreach($comments as $comment)
                <div class="bg-white border border-secondary/30 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-2 flex-grow">
                            {{-- Target/Source Context --}}
                            <div class="flex items-center flex-wrap gap-2 text-xs font-semibold">
                                @if($comment->commentable)
                                    @if($comment->commentable_type === 'App\Models\Blog')
                                        <span class="px-2 py-0.5 rounded bg-primary/10 text-primary uppercase text-[10px]">Blog</span>
                                        <span class="text-text/70">{{ $comment->commentable->title }}</span>
                                    @elseif($comment->commentable_type === 'App\Models\Project')
                                        <span class="px-2 py-0.5 rounded bg-accent/20 text-primary uppercase text-[10px]">Karya</span>
                                        <span class="text-text/70">{{ $comment->commentable->title }}</span>
                                    @endif
                                @else
                                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 uppercase text-[10px]">Dihapus</span>
                                    <span class="text-red-500 italic">Konten asli telah dihapus</span>
                                @endif

                                <span class="text-text/40">•</span>
                                <span class="text-text/50 font-normal">{{ $comment->created_at->format('d M Y, H:i') }}</span>

                                @if($comment->deleted_at)
                                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 uppercase text-[10px] ml-auto">Deleted</span>
                                @endif
                            </div>

                            {{-- Comment Content Snippet --}}
                            <p class="text-text font-medium text-base leading-relaxed break-words">
                                {{ Str::limit($comment->content, 100) }}
                            </p>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex-shrink-0">
                            @if($comment->commentable)
                                @php
                                    $targetUrl = '';
                                    if ($comment->commentable_type === 'App\Models\Blog') {
                                        $targetUrl = route('blog.show', $comment->commentable->slug) . '#comment-' . $comment->id;
                                    } elseif ($comment->commentable_type === 'App\Models\Project') {
                                        $targetUrl = route('projects.show', $comment->commentable->slug) . '#comment-' . $comment->id;
                                    }
                                @endphp
                                <a href="{{ $targetUrl }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-background font-semibold rounded-lg hover:bg-primary/95 transition-all text-sm shadow-sm hover:shadow active:scale-[0.98]">
                                    Lihat Konten
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @else
                                <button disabled class="inline-flex items-center gap-1.5 px-4 py-2 bg-secondary/15 border border-secondary/20 text-text/40 font-semibold rounded-lg text-sm cursor-not-allowed">
                                    Konten Dihapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination Links --}}
        <div class="mt-8">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection
