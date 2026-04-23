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
                    <span>{{ $blog->user->name ?? 'Admin' }}</span>
                </div>
            </header>

            @if($blog->thumbnail_path)
                <img src="{{ asset('storage/' . $blog->thumbnail_path) }}" alt="{{ $blog->title }}" class="blog-single-img">
            @endif

            <div class="blog-single-content">
                {!! nl2br(e($blog->content)) !!}
            </div>
        </article>

        {{-- Bagian Komentar --}}
        <section class="comments-section" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
            <h3>Komentar ({{ $blog->comments->count() + $blog->comments->flatMap->replies->count() }})</h3>
            
            @if(session('success'))
                <div style="padding: 10px; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Komentar Utama --}}
            @auth
                <div class="comment-form-container" style="margin-bottom: 30px;">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
                        <input type="hidden" name="commentable_type" value="{{ get_class($blog) }}">
                        <div style="margin-bottom: 10px;">
                            <textarea name="content" rows="3" placeholder="Tulis komentar Anda..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;" required></textarea>
                        </div>
                        <button type="submit" style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Kirim Komentar</button>
                    </form>
                </div>
            @else
                <div style="padding: 15px; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 30px; text-align: center;">
                    <p style="margin: 0;">Silakan <a href="{{ route('login.show') }}" style="color: #007bff; text-decoration: underline;">Login</a> atau <a href="{{ route('register.show') }}" style="color: #007bff; text-decoration: underline;">Daftar</a> untuk ikut berdiskusi.</p>
                </div>
            @endauth

            {{-- Daftar Komentar --}}
            <div class="comments-list">
                @forelse($blog->comments as $comment)
                    <div class="comment-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 4px; background-color: #fcfcfc;">
                        <div class="comment-header" style="margin-bottom: 8px;">
                            <strong>{{ $comment->user->name ?? 'User' }}</strong> 
                            <span style="color: #888; font-size: 0.85em;">• {{ $comment->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="comment-body" style="margin-bottom: 10px; white-space: pre-wrap;">{{ $comment->content }}</div>
                        
                        @auth
                            <button onclick="toggleReplyForm('{{ $comment->id }}')" style="background: none; border: none; color: #007bff; cursor: pointer; padding: 0; font-size: 0.9em;">Balas</button>
                        @endauth

                        {{-- Form Balasan (Hidden default) --}}
                        <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 15px; padding-left: 20px; border-left: 2px solid #ddd;">
                            <form action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
                                <input type="hidden" name="commentable_type" value="{{ get_class($blog) }}">
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <div style="margin-bottom: 10px;">
                                    <textarea name="content" rows="2" placeholder="Tulis balasan Anda..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;" required></textarea>
                                </div>
                                <button type="submit" style="background-color: #6c757d; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9em;">Kirim Balasan</button>
                                <button type="button" onclick="toggleReplyForm('{{ $comment->id }}')" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 6px 12px; font-size: 0.9em;">Batal</button>
                            </form>
                        </div>

                        {{-- Nested Replies --}}
                        @if($comment->replies->count() > 0)
                            <div class="replies-list" style="margin-top: 15px; padding-left: 20px; border-left: 2px solid #eee;">
                                @foreach($comment->replies as $reply)
                                    <div class="reply-item" style="margin-bottom: 15px; padding: 10px; background-color: #fff; border: 1px solid #f0f0f0; border-radius: 4px;">
                                        <div class="comment-header" style="margin-bottom: 5px;">
                                            <strong>{{ $reply->user->name ?? 'User' }}</strong> 
                                            <span style="color: #888; font-size: 0.85em;">• {{ $reply->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="comment-body" style="white-space: pre-wrap;">{{ $reply->content }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="color: #666; font-style: italic;">Belum ada komentar. Jadilah yang pertama!</p>
                @endforelse
            </div>
        </section>

        <script>
            function toggleReplyForm(commentId) {
                const form = document.getElementById('reply-form-' + commentId);
                if (form.style.display === 'none' || form.style.display === '') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            }
        </script>

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