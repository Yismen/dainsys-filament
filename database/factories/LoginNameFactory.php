<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LoginName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoginName>
 */
class LoginNameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'login_name' => $this->faker->unique()->text(),
            'employee_id' => Employee::factory(),
        ];
    }
}
