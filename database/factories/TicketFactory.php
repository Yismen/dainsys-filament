<?php

namespace Database\Factories;

use App\Enums\TicketStatuses;
use App\Enums\TicketPriorities;
use App\Models\TicketDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => UserFactory::new(),
            'department_id' => TicketDepartment::factory(),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->sentence(4),
            // 'assigned_to' => UserFactory::new(),
            // 'assigned_at' => now(),
            // 'expected_at' => now(),
            // 'completed_at' => now(),
            // 'reference' => 'watever',
            'status' => TicketStatuses::Pending->value,
            'priority' => TicketPriorities::Normal->value,
        ];
    }
}
