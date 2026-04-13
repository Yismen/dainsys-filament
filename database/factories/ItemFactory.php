<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'campaign_id' => Campaign::factory(),
            'price' => $this->faker->randomFloat(2, 1, 9999.99),
            'description' => $this->faker->sentence(),
        ];
    }
}
