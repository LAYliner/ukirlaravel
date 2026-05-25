<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class ContentHighlightController extends Controller
{
    /**
     * Handle redirect to content with comment highlight
     *
     * @param string $contentId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($contentId, Request $request)
    {
        $commentId = $request->query('highlightComment');

        // Validasi parameter highlightComment
        if (!$commentId) {
            // Jika tidak ada parameter highlightComment, coba redirect berdasarkan contentId saja
            // Coba cek di Blog dulu
            $blog = \App\Models\Blog::where('id', $contentId)->first();
            if ($blog) {
                return redirect()->route('blog.show', $blog->slug);
            }

            // Cek di Project
            $project = \App\Models\Project::where('id', $contentId)->first();
            if ($project) {
                return redirect()->route('projects.show', $project->slug);
            }

            abort(404, 'Konten tidak ditemukan');
        }

        // Fetch komentar dengan relasi lengkap
        $comment = Comment::with(['commentable', 'user'])->find($commentId);

        if (!$comment) {
            abort(404, 'Komentar tidak ditemukan');
        }

        // Cek jika komentar soft-deleted
        if ($comment->trashed()) {
            // Tetap redirect tapi dengan pesan error
            $target = $comment->commentable;
            if ($target instanceof \App\Models\Blog) {
                return redirect()
                    ->route('blog.show', $target->slug)
                    ->with('error', 'Komentar yang Anda cari telah dihapus oleh moderator.');
            } elseif ($target instanceof \App\Models\Project) {
                return redirect()
                    ->route('projects.show', $target->slug)
                    ->with('error', 'Komentar yang Anda cari telah dihapus oleh moderator.');
            }
        }

        // Tentukan tipe konten dan redirect
        $target = $comment->commentable;

        if (!$target) {
            abort(404, 'Konten asli komentar telah dihapus');
        }

        if ($target instanceof \App\Models\Blog) {
            // Redirect ke blog dengan parameter highlight
            return redirect()
                ->route('blog.show', $target->slug)
                ->with('highlightComment', $commentId);
        } elseif ($target instanceof \App\Models\Project) {
            // Redirect ke project dengan parameter highlight
            return redirect()
                ->route('projects.show', $target->slug)
                ->with('highlightComment', $commentId);
        }

        abort(404, 'Tipe konten tidak dikenali');
    }
}