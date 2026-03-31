@extends('layouts.public')

@section('title', $blog->title)

@section('content')
    <div class="container" style="max-width: 800px;">
        <article class="blog-single">
            <header class="blog-single-header">
                <h1 class="blog-single-title">{{ $blog->title }}</h1>
                <div class="blog-single-meta">
                    <span>{{ $blog->created_at->format('d M Y, H:i') }}</span>
                    <span> • </span>
                    <span>{{ $blog->admin->nama ?? 'Admin' }}</span>
                </div>
            </header>

            @if($blog->thumbnail)
                <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}" class="blog-single-img">
            @endif

            <div class="blog-single-content">
                {!! nl2br(e($blog->content)) !!}
            </div>
        </article>

        @if($relatedPosts->isNotEmpty())
            <section class="related-posts">
                <h3>Artikel Terkait</h3>
                <div class="blog-grid">
                    @foreach($relatedPosts as $post)
                        <article class="blog-card">
                            <div class="blog-card-body">
                                <h2 class="blog-card-title">{{ $post->title }}</h2>
                                <div class="blog-card-meta">
                                    <span>{{ $post->created_at->format('d M Y') }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-link">Baca →</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection