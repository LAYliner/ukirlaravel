<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->whereNull('deleted_at')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->whereNull('deleted_at')],
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