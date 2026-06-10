@extends('layouts.main')

@section('title', 'Profil Saya - Sanggar Ukir Tana Paser')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-4xl font-bold text-text mb-2">Pengaturan Profil</h1>
        <p class="text-text/80 font-medium">Kelola informasi pribadi, keamanan akun, dan lihat aktivitas komentar Anda.</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-8 p-4 text-sm text-primary bg-secondary/20 border-l-4 border-accent rounded-r-lg shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: User Summary & Navigation --}}
        <div class="space-y-8">
            <div class="bg-white border border-secondary/30 rounded-2xl p-6 shadow-sm flex flex-col items-center text-center">
                {{-- Picture --}}
                <div class="relative group mb-6">
                    <img id="avatar-preview" src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-secondary/50 shadow-inner group-hover:border-primary transition-all duration-300">
                    <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300 cursor-pointer" onclick="document.getElementById('profile_picture_input').click();">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-text mb-1">{{ $user->name }}</h2>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary uppercase mb-4">{{ $user->role }}</span>

                @if($user->bio)
                    <p class="text-sm text-text/80 italic mb-6">"{{ $user->bio }}"</p>
                @else
                    <p class="text-sm text-text/50 italic mb-6">Belum menambahkan bio singkat.</p>
                @endif

                <div class="w-full border-t border-secondary/20 pt-6 space-y-3">
                    <a href="{{ route('profile.comments') }}" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-text/80 hover:text-primary hover:bg-secondary/10 rounded-lg transition-colors group">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Riwayat Komentar
                        </span>
                        <span class="bg-secondary/35 text-primary text-xs px-2 py-0.5 rounded-full font-bold group-hover:bg-primary/20">{{ $user->comments()->count() }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column: Forms --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Edit Profile --}}
            <div class="bg-white border border-secondary/30 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text mb-6 pb-2 border-b border-secondary/20">Informasi Pribadi</h3>

                <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*" class="hidden">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-text mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-secondary/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-medium @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email (Read only) --}}
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1">Email <span class="text-xs text-text/50 font-normal">(Tidak dapat diubah)</span></label>
                            <input type="email" value="{{ $user->email }}" readonly class="w-full px-4 py-2 bg-secondary/10 border border-secondary/30 rounded-lg text-sm font-medium text-text/60 cursor-not-allowed">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-text mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2 border border-secondary/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-medium @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bio --}}
                        <div class="sm:col-span-2">
                            <label for="bio" class="block text-sm font-semibold text-text mb-1">Bio Singkat</label>
                            <textarea name="bio" id="bio" rows="3" class="w-full px-4 py-2 border border-secondary/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-medium @error('bio') border-red-500 @enderror">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2 bg-primary text-background font-semibold rounded-lg hover:bg-primary/95 transition-all text-sm shadow-sm hover:shadow active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white border border-secondary/30 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text mb-6 pb-2 border-b border-secondary/20">Keamanan & Password</h3>

                <form action="{{ route('profile.change-password') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-4">
                        {{-- Current Password --}}
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-text mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-2 border border-secondary/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-medium @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-text mb-1">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" required class="w-full px-4 py-2 border border-secondary/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-medium @error('new_password') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-text/60">Minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol.</p>
                            @error('new_password')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm New Password --}}
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-semibold text-text mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="w-full px-4 py-2 border border-secondary/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-medium">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2 bg-primary text-background font-semibold rounded-lg hover:bg-primary/95 transition-all text-sm shadow-sm hover:shadow active:scale-[0.98]">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Live preview for profile picture upload
    document.getElementById('profile_picture_input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal adalah 2 MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
