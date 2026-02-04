<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payroll>
 */
class PayrollFactory extends Factory
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
            'gross_income' => $this->faker->randomFloat(2, 100, 5000),
            'taxable_payroll' => $this->faker->randomFloat(2, 100, 5000),
            'hourly_rate' => $this->faker->randomFloat(2, 5, 50),
            'regular_hours' => $this->faker->randomFloat(2, 1, 160),
            'overtime_hours' => $this->faker->randomFloat(2, 0, 40),
            'holiday_hours' => $this->faker->randomFloat(2, 0, 24),
            'night_shift_hours' => $this->faker->randomFloat(2, 0, 24),
            'additional_incentives_1' => $this->faker->randomFloat(2, 0, 500),
            'additional_incentives_2' => $this->faker->randomFloat(2, 0, 500),
            'deduction_afp' => $this->faker->randomFloat(2, 0, 300),
            'deduction_ars' => $this->faker->randomFloat(2, 0, 300),
            'other_deductions' => $this->faker->randomFloat(2, 0, 300),
            'net_payroll' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
