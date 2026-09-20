<?php

namespace Database\Factories;

use App\Enums\PriorityLevel;
use App\Enums\QueueTicketOrigin;
use App\Enums\QueueTicketStatus;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\QueueTicket>
 */
class QueueTicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_number' => fake()->unique()->numerify('T-####'),
            'service_id' => Service::factory(),
            'user_id' => User::factory(),
            'origin' => QueueTicketOrigin::Espontaneo,
            'status' => QueueTicketStatus::Emitida,
            'priority_level' => PriorityLevel::Normal,
            'issued_at' => now(),
        ];
    }
}
