<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\DowntimeReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Performance>
 */
class PerformanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique_id' => $this->faker->unique()->word(),
            'file' => $this->faker->word(),
            'date' => now()->format('Y-m-d'),
            'employee_id' => Employee::factory(),
            'campaign_id' => Campaign::factory(),
            'campaign_goal' => 5.5555,
            'login_time' => 5.5555,
            'production_time' => 5.5555,
            'talk_time' => 5.5555,
            'billable_time' => 5.5555,
            'attempts' => rand(1, 90),
            'contacts' => rand(1, 90),
            'successes' => rand(1, 90),
            'upsales' => rand(1, 90),
            'revenue' => 5.5555,
            'downtime_reason_id' => DowntimeReason::factory(),
            'reporter_id' => Supervisor::factory(),
        ];
    }
}
