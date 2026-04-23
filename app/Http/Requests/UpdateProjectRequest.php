<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author'], true);
    }

    public function rules(): array
    {
        $project = $this->route('project');
        $projectId = $project instanceof \App\Models\Project ? $project->id : $project;

        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', Rule::unique('projects', 'slug')->ignore($projectId, 'id')],
            'description'  => ['required', 'string'],
            'client_name'  => ['nullable', 'string', 'max:255'],
            'project_date' => ['nullable', 'date'],
            'status'       => ['required', 'string', 'in:draft,published'],
            'is_visible'   => ['nullable', 'boolean'],
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