<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incentive>
 */
class IncentiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payable_date' => $this->faker->date(),
            'employee_id' => \App\Models\Employee::factory(),
            'project_id' => \App\Models\Project::factory(),
            'total_production_hours' => $this->faker->randomFloat(2, 1, 200),
            'total_sales' => $this->faker->randomFloat(2, 0, 10000),
            'amount' => $this->faker->randomFloat(2, 0, 5000),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
