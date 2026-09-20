<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Consulta Geral',
                'Vacinação',
                'Planeamento Familiar',
                'Consulta Pré-Natal',
                'Consulta Infantil',
                'Curativos',
            ]),
            'description' => fake()->sentence(),
            'average_duration_minutes' => fake()->numberBetween(10, 30),
            'active' => true,
        ];
    }
}
