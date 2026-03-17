<?php

namespace Database\Factories;

use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deduction>
 */
class DeductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'payable_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 0, 5000),
            'description' => $this->faker->sentence(),
        ];
    }
}
