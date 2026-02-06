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
            'salary_rate' => $this->faker->randomFloat(2, 5, 50),
            'total_hours' => $this->faker->randomFloat(2, 1, 200),
            'salary_income' => $this->faker->randomFloat(2, 100, 5000),
            'medical_licence' => $this->faker->randomFloat(2, 0, 500),
            'gross_income' => $this->faker->randomFloat(2, 100, 5000),
            'deduction_ars' => $this->faker->randomFloat(2, 0, 300),
            'deduction_afp' => $this->faker->randomFloat(2, 0, 300),
            'deductions_other' => $this->faker->randomFloat(2, 0, 300),
            'total_deductions' => $this->faker->randomFloat(2, 0, 900),
            'nightly_incomes' => $this->faker->randomFloat(2, 0, 500),
            'overtime_incomes' => $this->faker->randomFloat(2, 0, 500),
            'holiday_incomes' => $this->faker->randomFloat(2, 0, 500),
            'additional_incentives_1' => $this->faker->randomFloat(2, 0, 500),
            'additional_incentives_2' => $this->faker->randomFloat(2, 0, 500),
            'net_payroll' => $this->faker->randomFloat(2, 100, 5000),
            'total_payroll' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
