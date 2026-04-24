<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('user')
            ->where('status', 'published')
            ->where('is_visible', true)
            ->latest()
            ->paginate(9);

        return view('public.projects.index', compact('projects'));
    }

    public function show($slug)
    {
        $project = Project::with(['user', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('is_visible', true)
            ->firstOrFail();

        $relatedProjects = Project::where('status', 'published')
            ->where('is_visible', true)
            ->where('id', '!=', $project->id)
            ->latest()
            ->take(3)
            ->get();

        return view('public.projects.show', compact('project', 'relatedProjects'));
    }
}
