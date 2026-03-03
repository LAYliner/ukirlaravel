@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    <div class="hero">
        <h1>Blog Ukir</h1>
        <p>Temukan artikel dan cerita menarik di sini</p>
    </div>

    <div class="container">
        <div class="blog-grid">
            @forelse($blogs as $blog)
                <article class="blog-card">
                    @if($blog->thumbnail)
                        <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->judul }}" class="blog-card-img">
                    @else
                        <div class="blog-card-img" style="display:flex;align-items:center;justify-content:center;color:#999;">No Image</div>
                    @endif
                    <div class="blog-card-body">
                        <h2 class="blog-card-title">{{ $blog->judul }}</h2>
                        <div class="blog-card-meta">
                            <span>📅 {{ $blog->created_at->format('d M Y') }}</span>
                            <span> • </span>
                            <span>✍️ {{ $blog->admin->nama ?? 'Admin' }}</span>
                        </div>
                        <p class="blog-card-excerpt">{{ Str::limit(strip_tags($blog->isi), 150) }}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="blog-card-link">Baca Selengkapnya →</a>
                    </div>
                </article>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #666;">
                    <h2>Belum ada artikel</h2>
                    <p>Artikel akan segera hadir.</p>
                </div>
            @endforelse
        </div>

        @if($blogs->hasPages())
            <div class="pagination">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
@endsection