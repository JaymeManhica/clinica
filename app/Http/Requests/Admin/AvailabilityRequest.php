<?php

namespace App\Http\Requests\Admin;

use App\Models\Availability;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AvailabilityRequest extends FormRequest
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
        $service = $this->route('service');

        return [
            'professional_profile_id' => [
                'required',
                'integer',
                Rule::exists('professional_service', 'professional_profile_id')->where('service_id', $service->id),
            ],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function withValidator(ValidatorContract $validator): void
    {
        $validator->after(function (ValidatorContract $validator) {
            if ($validator->errors()->has('start_time') || $validator->errors()->has('end_time')) {
                return;
            }

            $availability = $this->route('availability');

            $overlap = Availability::query()
                ->where('professional_profile_id', $this->integer('professional_profile_id'))
                ->where('day_of_week', $this->integer('day_of_week'))
                ->when($availability, fn ($query) => $query->whereKeyNot($availability->id))
                ->where('start_time', '<', $this->input('end_time'))
                ->where('end_time', '>', $this->input('start_time'))
                ->exists();

            if ($overlap) {
                $validator->errors()->add(
                    'start_time',
                    'Já existe uma disponibilidade sobreposta para este profissional neste dia da semana.'
                );
            }
        });
    }
}
