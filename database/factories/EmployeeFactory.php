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
use App\Enums\PersonalIdTypes;
use Illuminate\Support\Facades\Date;
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
            'date_of_birth' => now(),
            'cellphone' => $this->faker->phoneNumber(),
            'status' => EmployeeStatus::Current,
            // 'marriage' => MaritalStatus::Single,
            'gender' => Gender::Male,
            'has_kids' => $this->faker->randomElement([0, 1]),
            'citizenship_id' => Citizenship::factory(),
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

    public function hired(Date|null $date = null) {
        if (!$date) {
            $date = now();
        }
        
        dd($date, $this);

    }
}
