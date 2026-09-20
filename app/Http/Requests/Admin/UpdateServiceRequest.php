<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdministrador() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('services', 'name')->ignore($this->route('service'))],
            'description' => ['nullable', 'string', 'max:1000'],
            'average_duration_minutes' => ['required', 'integer', 'between:5,240'],
        ];
    }
}
