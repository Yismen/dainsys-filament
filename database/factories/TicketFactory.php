<?php

namespace Database\Factories;

use App\Enums\TicketPriorities;
use App\Enums\TicketStatuses;
use App\Models\User;
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
            'owner_id' => User::factory(),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->sentence(4),
            'assigned_to' => null,
            'assigned_at' => null,
            'expected_at' => null,
            'completed_at' => null,
            // 'reference' => 'watever',
            'status' => TicketStatuses::Pending->value,
            'priority' => TicketPriorities::Normal->value,
        ];
    }

    public function unassigned()
    {
        return $this->state(function (array $atributes) {
            return [
                'assigned_to' => null,
                'assigned_at' => null,
            ];
        });
    }

    public function assigned()
    {
        return $this->state(function (array $atributes) {
            return [
                'assigned_to' => User::factory()->create(),
                'assigned_at' => now(),
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $atributes) {
            return [
                'assigned_to' => User::factory()->create(),
                'assigned_at' => now(),
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $atributes) {
            return [
                'completed_at' => now(),
            ];
        });
    }

    public function incompleted()
    {
        return $this->state(function (array $atributes) {
            return [
                'completed_at' => null,
            ];
        });
    }

    public function compliant()
    {
        return $this->state(function (array $atributes) {
            return [
                'completed_at' => now(),
            ];
        });
    }

    public function noncompliant()
    {
        return $this->state(function (array $atributes) {
            return [
                'completed_at' => now()->addDays(50),
            ];
        });
    }
}
