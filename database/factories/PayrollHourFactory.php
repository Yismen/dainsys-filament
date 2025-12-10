<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PayrollHour>
 */
class PayrollHourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'date' => $this->faker->date(),
            'total_hours' => $this->faker->randomFloat(2, 1, 12),
            'regular_hours' => $this->faker->randomFloat(2, 0, 8),
            'nightly_hours' => $this->faker->randomFloat(2, 0, 4),
            'overtime_hours' => $this->faker->randomFloat(2, 0, 4),
            'holiday_hours' => $this->faker->   randomFloat(2, 0, 8),
            'day_off_hours' => $this->faker->randomFloat(2, 0, 8),
        ];
    }
}
