<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\ProfessionalProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'professional_profile_id' => ProfessionalProfile::factory(),
            'service_id' => Service::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+2 weeks'),
            'status' => AppointmentStatus::Agendado,
        ];
    }
}
