<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tagId = $this->route('tag')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tagId)->whereNull('deleted_at')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($tagId)->whereNull('deleted_at')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama tag sudah digunakan.',
            'slug.unique' => 'Slug tag sudah digunakan.',
        ];
    }
}