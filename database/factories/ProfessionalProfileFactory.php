<?php

namespace Database\Factories;

use App\Models\ProfessionalProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfessionalProfile>
 */
class ProfessionalProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->profissional(),
            'title' => fake()->randomElement(['Médico Geral', 'Enfermeiro', 'Técnico de Medicina']),
            'registration_number' => fake()->unique()->numerify('OM-#####'),
        ];
    }
}
