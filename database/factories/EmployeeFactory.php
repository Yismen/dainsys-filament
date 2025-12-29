<?php

namespace Database\Factories;

use App\Enums\EmployeeStatuses;
use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Models\Citizenship;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

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
            'cellphone' => $this->faker->phoneNumber(),
            'status' => EmployeeStatuses::Hired,
            'gender' => Genders::Male,
            'has_kids' => $this->faker->randomElement([0, 1]),
            'citizenship_id' => Citizenship::factory(),
        ];
    }

    public function current(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeStatuses::Hired,
            ];
        });
    }

    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeStatuses::Terminated,
            ];
        });
    }

    public function suspended(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => EmployeeStatuses::Suspended,
            ];
        });
    }

    public function hired(?Date $date = null)
    {
        if (! $date) {
            $date = now();
        }

        dd($date, $this);

    }
}
