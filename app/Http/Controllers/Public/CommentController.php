<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

    /**
     * Delete a comment (soft delete) - Public endpoint
     * Only accessible by: comment owner, admin, or content author
     */
    public function destroy(string $id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $comment = Comment::with('commentable')->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan.'
            ], 404);
        }

        // Check if already deleted
        if ($comment->deleted_at) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar sudah dihapus.'
            ], 410);
        }

        // Validate access
        if (!$this->canDeleteComment($user, $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini.'
            ], 403);
        }

        // Perform soft delete
        $comment->deleted_at = now();
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus.',
            'data' => [
                'comment_id' => $comment->id,
                'deleted_at' => $comment->deleted_at
            ]
        ]);
    }

    /**
     * Check if user can delete a comment
     * Rules:
     * 1. Comment owner
     * 2. Admin
     * 3. Content author (blog/project owner)
     */
    private function canDeleteComment($user, Comment $comment): bool
    {
        // Rule 1: Comment owner
        if ($comment->user_id === $user->id) {
            return true;
        }

        // Rule 2: Admin
        if ($user->role === 'admin') {
            return true;
        }

        // Rule 3: Content author (blog/project owner)
        $commentable = $comment->commentable;

        if ($commentable instanceof Blog) {
            return $commentable->user_id === $user->id;
        }

        if ($commentable instanceof Project) {
            return $commentable->user_id === $user->id;
        }

        return false;
    }
}