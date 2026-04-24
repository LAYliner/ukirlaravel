@extends('layouts.main')

@section('title', $blog->title . ' - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- Blog Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-text mb-4">{{ $blog->title }}</h1>
        <div class="flex flex-wrap justify-center items-center gap-4 text-text/80 font-medium text-base">
            <span>Diterbitkan pada: {{ $blog->created_at->format('d M Y, H:i') }}</span>
            <span>•</span>
            <span>Penulis: {{ $blog->user->name ?? 'Admin' }}</span>
        </div>
    </div>

    {{-- Blog Thumbnail --}}
    <div class="mb-12 rounded-xl overflow-hidden bg-secondary/10 border border-secondary/30 shadow-sm">
        @if($blog->thumbnail_path)
            <img src="{{ asset('storage/' . $blog->thumbnail_path) }}" alt="{{ $blog->title }}" class="w-full h-auto object-cover max-h-[600px]">
        @else
            <div class="flex items-center justify-center w-full h-[400px] text-text/40">
                Tidak ada thumbnail
            </div>
        @endif
    </div>

    {{-- Blog Content --}}
    <div class="prose prose-stone prose-primary max-w-none mb-16 text-text/90 font-medium leading-relaxed">
        {!! nl2br(e($blog->content)) !!}
    </div>

    {{-- Comments Section --}}
    <section class="mt-16 pt-10 border-t border-secondary/30">
        <h3 class="text-2xl font-bold text-text mb-8">Komentar ({{ $blog->comments->count() + $blog->comments->flatMap->replies->count() }})</h3>
        
        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-700 border border-green-200 rounded-lg mb-8">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg mb-8">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Komentar Utama --}}
        @auth
            <div class="mb-10 bg-white p-6 rounded-lg border border-secondary/30 shadow-sm">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
                    <input type="hidden" name="commentable_type" value="{{ get_class($blog) }}">
                    <div class="mb-4">
                        <textarea name="content" rows="3" placeholder="Tulis pendapat Anda tentang artikel ini..." class="w-full p-3 bg-background border border-secondary/50 rounded text-text placeholder-text/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" required></textarea>
                    </div>
                    <button type="submit" class="bg-primary text-background font-medium px-6 py-2.5 rounded hover:bg-accent transition-colors shadow-sm">Kirim Komentar</button>
                </form>
            </div>
        @else
            <div class="p-6 bg-secondary/10 border border-secondary/30 rounded-lg mb-10 text-center">
                <p class="text-text/80 font-medium">Silakan <a href="{{ route('login.show') }}" class="text-primary hover:text-accent font-medium underline">Login</a> atau <a href="{{ route('register.show') }}" class="text-primary hover:text-accent font-medium underline">Daftar</a> untuk ikut berdiskusi.</p>
            </div>
        @endauth

        {{-- Daftar Komentar --}}
        <div class="space-y-6">
            @forelse($blog->comments as $comment)
                <div class="bg-white border border-secondary/30 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <strong class="text-primary">{{ $comment->user->name ?? 'User' }}</strong> 
                        <span class="text-text/70 font-medium text-base">• {{ $comment->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="text-text/90 font-medium leading-relaxed mb-4 whitespace-pre-wrap">{{ $comment->content }}</div>
                    
                    @auth
                        <button onclick="toggleReplyForm('{{ $comment->id }}')" class="text-base font-medium text-primary hover:text-accent transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            Balas
                        </button>
                    @endauth

                    {{-- Form Balasan (Hidden default) --}}
                    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 pl-4 border-l-2 border-secondary/30">
                        <form action="{{ route('comments.store') }}" method="POST" class="bg-secondary/5 p-4 rounded border border-secondary/20">
                            @csrf
                            <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
                            <input type="hidden" name="commentable_type" value="{{ get_class($blog) }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="mb-3">
                                <textarea name="content" rows="2" placeholder="Tulis balasan Anda..." class="w-full p-2.5 bg-white border border-secondary/40 rounded text-text placeholder-text/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" required></textarea>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-primary text-background px-4 py-2 rounded text-base hover:bg-accent transition-colors shadow-sm">Kirim Balasan</button>
                                <button type="button" onclick="toggleReplyForm('{{ $comment->id }}')" class="text-text/60 hover:text-text px-4 py-2 rounded text-base transition-colors border border-secondary/50 hover:bg-secondary/10">Batal</button>
                            </div>
                        </form>
                    </div>

                    {{-- Nested Replies --}}
                    @if($comment->replies->count() > 0)
                        <div class="mt-6 pl-6 border-l-2 border-secondary/30 space-y-4">
                            @foreach($comment->replies as $reply)
                                <div class="bg-secondary/5 border border-secondary/20 rounded p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <strong class="text-primary text-base">{{ $reply->user->name ?? 'User' }}</strong> 
                                        <span class="text-text/70 font-medium text-xs">• {{ $reply->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="text-text/90 font-medium leading-relaxed text-base whitespace-pre-wrap">{{ $reply->content }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-text/70 font-medium italic text-center py-8 bg-secondary/5 rounded-lg border border-secondary/20">Belum ada komentar. Jadilah yang pertama!</p>
            @endforelse
        </div>
    </section>

    {{-- Related Posts --}}
    @if($relatedPosts->isNotEmpty())
        <section class="mt-20 pt-12 border-t border-secondary/30">
            <h3 class="text-2xl font-bold text-text mb-8 text-center">Artikel Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group block bg-white border border-secondary/30 rounded-lg overflow-hidden hover:border-accent/50 transition-all duration-300 shadow-sm hover:shadow-md">
                        <div class="aspect-w-16 aspect-h-10 bg-secondary/10 relative">
                            @if($post->thumbnail_path)
                                <img src="{{ asset('storage/' . $post->thumbnail_path) }}" alt="{{ $post->title }}" class="object-cover w-full h-48 group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="flex items-center justify-center w-full h-48 text-text/40">Tanpa Gambar</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex items-center text-xs text-text/80 font-medium mb-2 gap-2">
                                <span>{{ $post->created_at->format('d M Y') }}</span>
                            </div>
                            <h4 class="text-lg font-bold text-text group-hover:text-primary transition-colors line-clamp-2">{{ $post->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }
</script>
@endpush