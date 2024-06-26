<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Afp;
use App\Models\Ars;
use App\Models\Site;
use App\Enums\Gender;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Supervisor;
use App\Models\Citizenship;
use App\Enums\MaritalStatus;
use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'second_first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'second_last_name' => $this->faker->lastName(),
            'full_name' => $this->faker->name(),
            'personal_id' => rand(10000000000, 99999999999),
            'hired_at' => now(),
            'date_of_birth' => now(),
            'cellphone' => $this->faker->phoneNumber(),
            'status' => EmployeeStatus::Current,
            'marriage' => MaritalStatus::Single,
            'gender' => Gender::Male,
            'kids' => $this->faker->randomElement([0, 1]),
            'punch' => rand(10000, 99999),
            'site_id' => Site::factory(),
            'project_id' => Project::factory(),
            'position_id' => Position::factory(),
            'citizenship_id' => Citizenship::factory(),
            'supervisor_id' => Supervisor::factory(),
            'afp_id' => Afp::factory(),
            'ars_id' => Ars::factory(),
        ];
    }

    public function current(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeStatus::Current,
            ];
        });
    }

    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeStatus::Inactive,
            ];
        });
    }

    public function suspended(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeStatus::Suspended,
            ];
        });
    }
}
