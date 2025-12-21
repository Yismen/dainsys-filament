<?php

namespace Database\Factories;

use App\Models\Information;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Information>
 */
class InformationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Information::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'photos' => [],
            'address' => $this->faker->address(),
            'company_id' => Str::random(10),
            'informationable_id' => 1,
            'informationable_type' => Site::class,
        ];
    }
}
