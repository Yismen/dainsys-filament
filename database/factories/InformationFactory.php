<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\Information;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'photo_url' => $this->faker->url(),
            'address' => $this->faker->address(),
            'company_id' => Str::random(10),
            'informationable_id' => 1,
            'informationable_type' => Site::class,
        ];
    }
}
