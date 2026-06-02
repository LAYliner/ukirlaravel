<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['user', 'tags'])
            ->where('status', 'published')
            ->where('is_visible', true)
            ->latest()
            ->paginate(9);

        return view('public.projects.index', compact('projects'));
    }

    public function show($slug)
    {
        $project = Project::with(['user', 'tags', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
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