<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incentive>
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
            'employee_id' => Employee::factory(),
            'project_id' => Project::factory(),
            'total_production_hours' => $this->faker->randomFloat(2, 1, 200),
            'total_sales' => $this->faker->randomFloat(2, 0, 10000),
            'amount' => $this->faker->randomFloat(2, 0, 5000),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
