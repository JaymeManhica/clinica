<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfessionalRequest extends FormRequest
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
        $profile = $this->route('professional');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($profile->user_id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($profile->user_id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'title' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:50', Rule::unique('professional_profiles', 'registration_number')->ignore($profile)],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
        ];
    }
}
