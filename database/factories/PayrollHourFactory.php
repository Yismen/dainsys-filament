<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\PayrollHour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollHour>
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
            'employee_id' => Employee::factory(),
            'date' => $this->faker->date(),
            'total_hours' => $this->faker->randomFloat(2, 1, 12),
            // 'regular_hours' => $this->faker->randomFloat(2, 0, 8),
            // 'overtime_hours' => $this->faker->randomFloat(2, 0, 4),
            // 'holiday_hours' => $this->faker->randomFloat(2, 0, 8),
            // 'seventh_day_hours' => $this->faker->randomFloat(2, 0, 8),
        ];
    }
}
