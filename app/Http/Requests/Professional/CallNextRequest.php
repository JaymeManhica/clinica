<?php

namespace App\Http\Requests\Professional;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CallNextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isProfissional() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $profile = $this->user()->professionalProfile;

        return [
            'service_id' => [
                'required',
                'integer',
                Rule::exists('professional_service', 'service_id')->where('professional_profile_id', $profile?->id),
            ],
        ];
    }
}
