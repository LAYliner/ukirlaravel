<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()->forAuthenticatedUser(auth()->user());

        $query->filterByStatus($request->string('status')->toString())
            ->filterByAuthor($request->string('author_id')->toString())
            ->filterByDateRange(
                $request->string('date_from')->toString(),
                $request->string('date_to')->toString()
            )
            ->search($request->string('q')->toString());

        // Fetch author list untuk dropdown (hanya kolom yang diperlukan)
        $authors = User::whereIn('role', ['admin', 'author'])
                    ->orderBy('name')
                    ->get(['id', 'name']);

        $allowedColumns = ['title', 'client_name', 'views', 'created_at', 'updated_at'];
        $sortField = in_array($request->string('sort')->toString(), $allowedColumns, true) 
            ? $request->string('sort')->toString() 
            : 'created_at';
            
        $sortDirection = $request->string('dir')->lower()->toString() === 'asc' ? 'asc' : 'desc';

        $projects = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.projects.index', compact('projects', 'authors'));
    }

    public function create(): View
    {
        return view('admin.projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';
        $validated['is_visible'] = false;

        Project::create($validated);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project berhasil dibuat.');
    }

    public function show(string $id): View
    {
        $project = Project::query()
                          ->forAuthenticatedUser(auth()->user())
                          ->findOrFail($id);

        return view('admin.projects.show', compact('project'));
    }

    public function edit(string $id): View
    {
        $project = Project::query()
                          ->forAuthenticatedUser(auth()->user())
                          ->findOrFail($id);

        return view('admin.projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, string $id): RedirectResponse
    {
        $project = Project::query()
                          ->forAuthenticatedUser(auth()->user())
                          ->findOrFail($id);

        $validated = $request->validated();

        if ($project->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $id);
        } else {
            $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : $project->slug;
        }

        if (auth()->user()->role !== 'admin' && isset($validated['status'])) {
            unset($validated['status']);
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $project = Project::query()
                          ->forAuthenticatedUser(auth()->user())
                          ->findOrFail($id);

        $project->delete();

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project berhasil dipindahkan ke trash.');
    }

    public function toggleVisibility(string $id): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Akses ditolak.');

        $project = Project::query()->findOrFail($id);

        // Blokir toggle pada status draft
        if ($project->status !== 'published') {
            return redirect()->back()->with('error', 'Visibilitas hanya dapat diubah pada project yang sudah Published.');
        }

        $project->update([
            'is_visible' => !$project->is_visible,
            'updated_at' => now(),
        ]);

        $action = $project->is_visible ? 'dipublikasikan' : 'diunpublish';
        return redirect()->back()->with('success', "Project berhasil {$action}.");
    }

    public function updateStatus(string $id, Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Akses ditolak.');

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:draft,published'],
        ]);

        $project = Project::query()->findOrFail($id);

        if ($project->status === $validated['status']) return redirect()->back();

        // Auto-couple visibility dengan status
        if ($validated['status'] === 'published') {
            // Draft -> Published: otomatis visible
            $project->update([
                'status' => 'published',
                'is_visible' => true,
                'published_at' => now(), // Jika kolom tersedia
            ]);
        } else {
            // Published -> Draft: otomatis hidden
            $project->update([
                'status' => 'draft',
                'is_visible' => false,
            ]);
        }

        return redirect()->back()->with('success', "Status project berhasil diubah ke {$validated['status']}.");
    }

    protected function generateUniqueSlug(string $title, ?string $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = Project::query()->where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = Project::query()->where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }
}