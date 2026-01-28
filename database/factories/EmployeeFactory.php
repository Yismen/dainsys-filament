<?php

namespace Database\Factories;

use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
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
            'personal_id_type' => PersonalIdTypes::DominicanId,
            'personal_id' => rand(10000000000, 99999999999),
            'date_of_birth' => now()->subYears(18)->format('Y-m-d'),
            'cellphone' => $this->faker->unique()->numerify('809#######'),
            'secondary_phone' => $this->faker->numerify('809#######'),
            'email' => $this->faker->unique()->email(),
            'address' => $this->faker->address(),
            'gender' => Genders::Male,
            'has_kids' => $this->faker->randomElement([0, 1]),
            'citizenship_id' => Citizenship::factory(),
        ];
    }

    public function hired(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'site_id' => Site::factory(),
                'project_id' => Project::factory(),
                'position_id' => Position::factory(),
                'supervisor_id' => Supervisor::factory(),
                'hired_at' => now(),
            ];
        });
    }
}
