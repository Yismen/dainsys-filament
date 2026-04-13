<?php

namespace Database\Factories;

use App\Models\InvoiceCancellation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceCancellation>
 */
class InvoiceCancellationFactory extends Factory
{
    protected $model = InvoiceCancellation::class;

    public function definition(): array
    {
        return [
            'invoice_id' => null,
            'cancelled_by' => null,
            'date' => $this->faker->date(),
            'reason' => $this->faker->sentence(),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
