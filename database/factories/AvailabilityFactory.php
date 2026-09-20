<?php

namespace Database\Factories;

use App\Models\ProfessionalProfile;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'professional_profile_id' => ProfessionalProfile::factory(),
            'service_id' => Service::factory(),
            'day_of_week' => fake()->numberBetween(1, 5),
            'start_time' => '07:30:00',
            'end_time' => '15:30:00',
            'active' => true,
        ];
    }
}
