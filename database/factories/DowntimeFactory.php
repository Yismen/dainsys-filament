<?php

namespace Database\Factories;

use App\Enums\DowntimeStatuses;
use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Downtime>
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
            'date' => now()->toDateString(),
            'employee_id' => Employee::factory(),
            'campaign_id' => Campaign::factory(state: ['revenue_type' => RevenueTypes::Downtime]),
            'downtime_reason_id' => DowntimeReason::factory(),
            'total_time' => 4,
            'status' => DowntimeStatuses::Pending,
            // 'requester_id' => User::factory(),
            // 'aprover_id' => User::factory(),
        ];
    }

    public function aproved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DowntimeStatuses::Approved,
                'aprover_id' => User::factory(),
            ];
        });
    }
}
