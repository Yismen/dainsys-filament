<?php

namespace Database\Factories;

use App\Enums\RevenueTypes;
use App\Models\User;
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
            'date' =>  now()->toDateString(),
            'employee_id' => \App\Models\Employee::factory(),
            'campaign_id' => \App\Models\Campaign::factory(state: ['revenue_type' => RevenueTypes::Downtime]),
            'downtime_reason_id' => \App\Models\DowntimeReason::factory(),
            'time' => 4,
            'requester_id' => User::factory(),
            'aprover_id' => User::factory(),
        ];
    }
}
