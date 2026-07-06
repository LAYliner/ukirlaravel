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
        {!! $blog->content !!}
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
                @php
                    $isDeleted = $comment->deleted_at !== null;
                    $canDelete = false;
                    if (auth()->check()) {
                        $user = auth()->user();
                        $isOwner = $comment->user_id === $user->id;
                        $isAdmin = $user->role === 'admin';
                        $isAuthor = $blog->user_id === $user->id;
                        $canDelete = $isOwner || $isAdmin || $isAuthor;
                    }
                @endphp
                <div id="comment-{{ $comment->id }}"
                     data-comment-id="{{ $comment->id }}"
                     data-is-deleted="{{ $isDeleted ? 'true' : 'false' }}"
                     class="bg-white border border-secondary/30 rounded-lg p-6 shadow-sm comment-container {{ $isDeleted ? 'opacity-75 bg-gray-50' : '' }}">
                    <div class="flex items-center gap-3 mb-3">
                        @if($isDeleted)
                            <strong class="text-gray-400">[Dihapus]</strong>
                            <span class="text-text/70 font-medium text-base">• Komentar ini telah dihapus</span>
                        @else
                            <strong class="text-primary">{{ $comment->user->name ?? 'User' }}</strong>
                            <span class="text-text/70 font-medium text-base">• {{ $comment->created_at->format('d M Y, H:i') }}</span>
                        @endif
                    </div>
                    <div class="text-text/90 font-medium leading-relaxed mb-4 whitespace-normal">
                        @if($isDeleted)
                            <span class="text-gray-400 italic">[Komentar ini telah dihapus]</span>
                        @else
                            {{ $comment->content }}
                        @endif
                    </div>

                    @if(!$isDeleted && auth()->check())
                        <div class="flex items-center gap-4">
                            <button onclick="toggleReplyForm('{{ $comment->id }}')" class="text-base font-medium text-primary hover:text-accent transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                Balas
                            </button>
                            @if($canDelete)
                                <button onclick="confirmDeleteComment('{{ $comment->id }}')" class="text-base font-medium text-red-600 hover:text-red-800 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            @endif
                        </div>
                    @endif

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
                                @php
                                    $isReplyDeleted = $reply->deleted_at !== null;
                                    $canDeleteReply = false;
                                    if (auth()->check()) {
                                        $user = auth()->user();
                                        $isReplyOwner = $reply->user_id === $user->id;
                                        $isAdmin = $user->role === 'admin';
                                        $isAuthor = $blog->user_id === $user->id;
                                        $canDeleteReply = $isReplyOwner || $isAdmin || $isAuthor;
                                    }
                                @endphp
                                <div id="comment-{{ $reply->id }}"
                                     data-comment-id="{{ $reply->id }}"
                                     data-is-deleted="{{ $isReplyDeleted ? 'true' : 'false' }}"
                                     class="bg-secondary/5 border border-secondary/20 rounded p-4 comment-container {{ $isReplyDeleted ? 'opacity-75 bg-gray-50' : '' }}">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if($isReplyDeleted)
                                            <strong class="text-gray-400 text-base">[Dihapus]</strong>
                                            <span class="text-text/70 font-medium text-xs">• Komentar ini telah dihapus</span>
                                        @else
                                            <strong class="text-primary text-base">{{ $reply->user->name ?? 'User' }}</strong>
                                            <span class="text-text/70 font-medium text-xs">• {{ $reply->created_at->format('d M Y, H:i') }}</span>
                                        @endif
                                    </div>
                                    <div class="text-text/90 font-medium leading-relaxed text-base whitespace-normal">
                                        @if($isReplyDeleted)
                                            <span class="text-gray-400 italic text-sm">[Komentar ini telah dihapus]</span>
                                        @else
                                            {{ $reply->content }}
                                        @endif
                                    </div>
                                    @if(!$isReplyDeleted && auth()->check() && $canDeleteReply)
                                        <button onclick="confirmDeleteComment('{{ $reply->id }}')" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors flex items-center gap-1 mt-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus
                                        </button>
                                    @endif
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

    // Modal konfirmasi penghapusan komentar
    function confirmDeleteComment(commentId) {
        if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
            deleteComment(commentId);
        }
    }

    // Fungsi untuk menghapus komentar via AJAX
    function deleteComment(commentId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                          document.querySelector('[name="_token"]')?.value;

        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '/login';
                return null;
            }
            if (response.status === 403) {
                alert('Anda tidak memiliki izin untuk menghapus komentar ini.');
                return null;
            }
            if (response.status === 404) {
                alert('Komentar tidak ditemukan.');
                return null;
            }
            if (response.status === 410) {
                alert('Komentar sudah dihapus sebelumnya.');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                // Update UI tanpa reload
                updateDeletedCommentUI(commentId);

                // Tampilkan toast/notifikasi
                showToast('Komentar berhasil dihapus');
            }
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('Terjadi kesalahan saat menghapus komentar. Silakan coba lagi.');
        });
    }

    // Update tampilan komentar yang telah dihapus
    function updateDeletedCommentUI(commentId) {
        const commentElement = document.getElementById('comment-' + commentId);
        if (!commentElement) return;

        // Update user info
        const userInfoDiv = commentElement.querySelector('.flex.items-center.gap-3.mb-3') ||
                           commentElement.querySelector('.flex.items-center.gap-3.mb-2');
        if (userInfoDiv) {
            const isReply = userInfoDiv.querySelector('strong.text-base');
            if (isReply) {
                userInfoDiv.innerHTML = '<strong class="text-gray-400 text-base">[Dihapus]</strong>' +
                                       '<span class="text-text/70 font-medium text-xs">• Komentar ini telah dihapus</span>';
            } else {
                userInfoDiv.innerHTML = '<strong class="text-gray-400">[Dihapus]</strong>' +
                                       '<span class="text-text/70 font-medium text-base">• Komentar ini telah dihapus</span>';
            }
        }

        // Update content
        const contentDiv = commentElement.querySelector('.text-text\\/90.font-medium.leading-relaxed');
        if (contentDiv) {
            const isReply = commentElement.querySelector('.text-base.whitespace-normal');
            if (isReply) {
                contentDiv.innerHTML = '<span class="text-gray-400 italic text-sm">[Komentar ini telah dihapus]</span>';
            } else {
                contentDiv.innerHTML = '<span class="text-gray-400 italic">[Komentar ini telah dihapus]</span>';
            }
        }

        // Hide action buttons (Balas, Hapus)
        const actionsDiv = commentElement.querySelector('.flex.items-center.gap-4');
        if (actionsDiv) {
            actionsDiv.style.display = 'none';
        }

        // Remove delete button for replies
        const deleteBtn = commentElement.querySelector('button[onclick*="confirmDeleteComment"]');
        if (deleteBtn) {
            deleteBtn.remove();
        }

        // Add visual indicator
        commentElement.classList.add('opacity-75', 'bg-gray-50');
        commentElement.setAttribute('data-is-deleted', 'true');
    }

    // Toast notification sederhana
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-y-0 opacity-100';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Auto-scroll dan highlight komentar jika ada parameter highlightComment
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const highlightCommentId = urlParams.get('highlightComment');

        if (highlightCommentId) {
            const commentElement = document.getElementById('comment-' + highlightCommentId);

            if (commentElement) {
                // Smooth scroll ke komentar
                commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Tambahkan class highlight
                setTimeout(() => {
                    commentElement.classList.add('highlight-active');
                }, 800);

                // Hapus highlight setelah 4 detik
                setTimeout(() => {
                    commentElement.classList.remove('highlight-active');
                }, 4800);
            }
        }
    });
</script>
@endpush