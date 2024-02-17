<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Department;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Position::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'department_id' => Department::factory(),
            'payment_type_id' => PaymentType::factory(),
            'salary' => rand(80, 200),
            'description' => $this->faker->paragraph(),
        ];
    }
}
