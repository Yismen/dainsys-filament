<?php

namespace Database\Factories;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Production>
 */
class ProductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'unique_id' => $this->faker->word(),
            'date' => now()->format('Y-m-d'),
            'employee_id' => Employee::factory(),
            'campaign_id' => Campaign::factory(),
            // 'supervisor_id' => Supervisor::factory(),
            // 'revenue_type' => RevenueTypes::LoginTime,
            // 'revenue_rate' => 0,
            // 'sph_goal' => 0,
            'conversions' => rand(1, 90),
            'total_time' => 0,
            'production_time' => 0,
            'talk_time' => 0,
            // 'billable_time' => 0,
            // 'revenue' => 0,
        ];
    }
}
