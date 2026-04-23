<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commentable_id' => ['required', 'string'],
            'commentable_type' => ['required', 'string'],
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'string', 'exists:comments,id'],
        ]);

        $validated['id'] = Str::uuid()->toString();
        $validated['user_id'] = auth()->id(); // Akan selalu ada karena dilindungi middleware auth

        Comment::create($validated);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
