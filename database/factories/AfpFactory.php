<?php

namespace Database\Factories;

use App\Models\Afp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Afp>
 */
class AfpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Afp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'person_of_contact' => $this->faker->name(),
            'phone' => $this->faker->numerify('809#######'),
            'description' => $this->faker->text(),
        ];
    }
}
