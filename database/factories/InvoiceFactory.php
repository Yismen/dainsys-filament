<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'number' => 'INV-'.strtoupper($this->faker->ean8),
            'date' => $this->faker->date(),
            'project_id' => Project::factory(),
            'agent_id' => InvoiceAgent::factory(),
            'campaign_id' => Campaign::factory(),
            'items' => [
                ['name' => 'Test Item', 'price' => 10.0],
            ],
            'subtotal_amount' => $this->faker->randomFloat(2, 50, 1000),
            'tax_amount' => $this->faker->randomFloat(2, 5, 200),
            'total_amount' => $this->faker->randomFloat(2, 60, 1300),
            'total_paid' => $this->faker->randomFloat(2, 0, 1300),
            'balance_pending' => $this->faker->randomFloat(2, 0, 800),
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid']),
            'due_date' => $this->faker->date(),
        ];
    }
}
