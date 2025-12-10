<?php

namespace Database\Factories;

use \App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Downtime>
 */
class DowntimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'employee_id' => \App\Models\Employee::factory(),
            'campaign_id' => \App\Models\Campaign::factory(),
            'downtime_reason_id' => \App\Models\DowntimeReason::factory(),
            'time' => $this->faker->time(),
            'requester_id' => User::factory(),
            'aprover_id' => User::factory(),
        ];
    }
}
