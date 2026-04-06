@extends('layouts.admin')
@section('title', 'Manajemen Blog')
@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
      <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h1>Daftar Blog</h1>
        <a href="{{ route('admin.blog.create') }}" style="padding: 0.5rem 1rem; background: var(--accent-color); color: #fff; border-radius: 4px; text-decoration: none;">
            + Buat Baru
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; text-align: left;">
                <th style="padding: 1rem; border-bottom: 2px solid var(--border-color);">Judul</th>
                <th style="padding: 1rem; border-bottom: 2px solid var(--border-color);">Status</th>
                <th style="padding: 1rem; border-bottom: 2px solid var(--border-color);">Tanggal</th>
                <th style="padding: 1rem; border-bottom: 2px solid var(--border-color);">Penulis</th>
                <th style="padding: 1rem; border-bottom: 2px solid var(--border-color);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blogs as $blog)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;">{{ $blog->title }}</td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; background: {{ $blog->status === 'published' ? '#d4edda' : ($blog->status === 'rejected' ? '#f8d7da' : '#fff3cd') }}; color: {{ $blog->status === 'published' ? '#155724' : ($blog->status === 'rejected' ? '#721c24' : '#856404') }};">
                            {{ ucfirst($blog->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">{{ $blog->created_at->format('d M Y, H:i') }}</td>
                    <td style="padding: 1rem;">{{ $blog->user->name ?? 'N/A' }}</td>
                    <td style="padding: 1rem;">
                        <a href="{{ route('admin.blog.edit', $blog->id) }}" style="color: var(--accent-color); text-decoration: none; margin-right: 0.5rem;">Edit</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $blog->id) }}" style="display: inline;" onsubmit="return confirm('Hapus blog ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc3545; background: none; border: none; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: #666;">Belum ada blog.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $blogs->links() }}
    </div>
</div>
@endsection