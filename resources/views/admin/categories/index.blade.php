@extends('layouts.admin')
@section('title', 'Kategori')

@section('content')
@php
    $sortDirection = request('sort_direction', 'asc');
    $nextSortDirection = $sortDirection === 'asc' ? 'desc' : 'asc';
    $sortIcon = $sortDirection === 'asc' ? '▲' : '▼';
@endphp
<div style= "max-width: 1200px; margin: 0 auto;">  <!-- "margin-top: 2rem;">-->
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h1>Kategori</h1>
        
        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <!-- Search Box -->
            <form method="GET" action="{{ route('admin.categories.index') }}" style="display: flex;">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search" 
                    value="{{ request('search') }}"
                    style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px 0 0 4px; outline: none; width: 200px;"
                >
                <button 
                    type="submit" 
                    style="padding: 0.5rem 1rem; background: #3498db; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer;"
                >
                    🔍
                </button>
            </form>
            
            <!-- Create Button -->
            <a href="{{ route('admin.categories.create') }}" style="background: #3498db; color: white; padding: 0.5rem 1.5rem; text-decoration: none; border-radius: 4px; font-weight: 500;">
                + Buat Baru
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; border-left: 4px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; border-left: 4px solid #dc3545;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #495057; width: 40%;">
                        <a href="{{ route('admin.categories.index', array_merge(request()->except('sort_direction'), ['sort_direction' => $nextSortDirection])) }}" 
                        style="text-decoration: none; color: inherit; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            Nama 
                            <span style="font-size: 0.75rem; color: {{ request()->has('sort_direction') ? '#3498db' : '#adb5bd' }};">
                                {{ $sortIcon }}
                            </span>
                        </a>
                    </th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #495057;">Deskripsi</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr style="border-bottom: 1px solid #dee2e6; transition: background 0.2s;">
                    <td style="padding: 1rem; color: #2c3e50;">{{ $category->name }}</td>
                    <td style="padding: 1rem; color: #6c757d;">{{ Str::limit($category->description, 50) ?? '-' }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" style="background: #ffc107; color: #000; padding: 0.4rem 0.8rem; text-decoration: none; border-radius: 4px; font-size: 0.875rem; margin-right: 0.25rem;">
                            Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 4px; font-size: 0.875rem; cursor: pointer;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 2rem; text-align: center; color: #6c757d;">
                        Tidak ada kategori. <a href="{{ route('admin.categories.create') }}" style="color: #3498db;">Buat kategori baru</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($categories, 'links'))
        <div class="pagination" style="margin-top: 1rem;">
            {{ $categories->appends(request()->except('page'))->links() }}
        </div>
    @endif
</div>
@endsection