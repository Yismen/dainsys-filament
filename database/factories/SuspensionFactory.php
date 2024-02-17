<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Suspension;
use App\Models\SSuspensionite;
use App\Models\SuspensionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suspension>
 */
class SuspensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Suspension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'suspension_type_id' => SuspensionType::factory(),
            'starts_at' => now(),
            'ends_at' => now(),
            'comments' => $this->faker->paragraph(),
        ];
    }
}
