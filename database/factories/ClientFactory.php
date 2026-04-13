<?php

namespace Database\Factories;

use App\Models\Client;
use App\Services\InvoiceTemplatesService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company().' '.Str::uuid(),
            'person_of_contact' => $this->faker->name(),
            'phone' => $this->faker->numerify('809#######'),
            'email' => $this->faker->email(),
            'website' => $this->faker->url(),
            'description' => $this->faker->text(),
            'invoice_template' => $this->faker->randomElement(array_keys(InvoiceTemplatesService::make())),
            'date_field_name' => $this->faker->word,
            'project_field_name' => $this->faker->word,
        ];
    }
}
