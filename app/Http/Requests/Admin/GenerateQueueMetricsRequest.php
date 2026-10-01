<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GenerateQueueMetricsRequest extends FormRequest
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
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after:period_start', 'before_or_equal:now'],
        ];
    }
}
