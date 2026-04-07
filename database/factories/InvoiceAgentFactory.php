<?php

namespace Database\Factories;

use App\Models\InvoiceAgent;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceAgent>
 */
class InvoiceAgentFactory extends Factory
{
    protected $model = InvoiceAgent::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'project_id' => Project::factory(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}
