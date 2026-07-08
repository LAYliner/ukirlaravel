<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'author'], true)) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        // Author can only update their own project
        $project = $this->route('project');
        $projectId = $project instanceof \App\Models\Project ? $project->id : $project;
        $ownerId = \App\Models\Project::where('id', $projectId)->value('user_id');

        return $ownerId === $user->id;
    }

    public function rules(): array
    {
        $project = $this->route('project');
        $projectId = $project instanceof \App\Models\Project ? $project->id : $project;

        return [
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', Rule::unique('projects', 'slug')->ignore($projectId, 'id')],
            'description'    => ['required', 'string'],
            'client_name'    => ['nullable', 'string', 'max:255'],
            'thumbnail_path' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'status'         => ['required', 'string', 'in:draft,published'],
            'is_visible'     => ['nullable', 'boolean'],
            'tags'           => ['nullable', 'array'],
            'tags.*'         => ['string', 'exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul project wajib diisi.',
            'title.max'            => 'Judul project maksimal 255 karakter.',
            'slug.unique'          => 'Slug project sudah digunakan.',
            'description.required' => 'Deskripsi project wajib diisi.',
            'status.in'            => 'Status tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title'       => trim($this->string('title')),
            'client_name' => trim($this->string('client_name')),
            'slug'        => $this->string('slug')->toString() !== '' ? trim($this->string('slug')) : null,
        ]);
    }
}