<?php

namespace Database\Factories;

use App\Models\Ars;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ars>
 */
class ArsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ars::class;

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
