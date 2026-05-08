<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Display a listing of comments with pagination, sorting, and search.
     */
    public function index(Request $request): View
    {
        $query = Comment::with(['user', 'commentable']);

        // Search by content, user name, or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type (blog/project)
        if ($type = $request->input('type')) {
            if (in_array($type, ['blog', 'project'])) {
                $modelClass = $type === 'blog' ? 'App\\Models\\Blog' : 'App\\Models\\Project';
                $query->where('commentable_type', $modelClass);
            }
        }

        // Filter by specific blog_id
        if ($blogId = $request->input('blog_id')) {
            $query->where('commentable_type', 'App\\Models\\Blog')
                  ->where('commentable_id', $blogId);
        }

        // Filter by specific project_id
        if ($projectId = $request->input('project_id')) {
            $query->where('commentable_type', 'App\\Models\\Project')
                  ->where('commentable_id', $projectId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } elseif ($status === 'all') {
                $query->withTrashed();
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSortFields = ['id', 'created_at', 'updated_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array(strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $comments = $query->paginate(15)->withQueryString();

        // Get all blogs and projects for filter dropdowns
        $blogs = \App\Models\Blog::orderBy('title')->get();
        $projects = \App\Models\Project::orderBy('title')->get();

        return view('admin.comments.index', compact('comments', 'blogs', 'projects'));
    }

    /**
     * Soft delete a comment.
     */
    public function destroy(string $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        $comment->delete(); // Soft delete

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Restore a soft-deleted comment.
     */
    public function restore(string $id): RedirectResponse
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->restore();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dipulihkan.');
    }

    /**
     * Permanently delete a comment.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->forceDelete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dihapus permanen.');
    }
}