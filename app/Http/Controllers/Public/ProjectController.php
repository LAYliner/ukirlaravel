<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        // Sanitasi input search dan tags
        $search = $request->input('search');
        $tagIds = $request->input('tags', []);

        // Pastikan tagIds adalah array
        if (!is_array($tagIds)) {
            $tagIds = [$tagIds];
        }

        // Query project dengan scope published, search, dan filter tags
        $projects = Project::published()
            ->with(['user', 'tags'])
            ->search($search)
            ->filterByTags($tagIds)
            ->latest()
            ->paginate(9)
            ->appends($request->only('search', 'tags'));

        // Ambil semua tags untuk dropdown filter (hanya yang memiliki project published)
        $tags = Tag::whereHas('projects', function ($query) {
                $query->published();
            })
            ->active()
            ->orderBy('name')
            ->get();

        return view('public.projects.index', compact('projects', 'tags'));
    }

    public function show($slug)
    {
        $project = Project::with(['user', 'tags', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest()->withTrashed();
            }])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('is_visible', true)
            ->firstOrFail();

        // Get related projects by tags (projects that share at least one tag)
        $projectTagIds = $project->tags->pluck('id')->toArray();

        if (!empty($projectTagIds)) {
            $relatedProjects = Project::whereHas('tags', function($q) use ($projectTagIds) {
                    $q->whereIn('tags.id', $projectTagIds);
                })
                ->where('status', 'published')
                ->where('is_visible', true)
                ->where('id', '!=', $project->id)
                ->latest()
                ->take(3)
                ->get();
        } else {
            $relatedProjects = Project::where('status', 'published')
                ->where('is_visible', true)
                ->where('id', '!=', $project->id)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('public.projects.show', compact('project', 'relatedProjects'));
    }
}