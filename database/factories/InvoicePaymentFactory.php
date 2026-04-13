<?php

namespace Database\Factories;

use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoicePayment>
 */
class InvoicePaymentFactory extends Factory
{
    protected $model = InvoicePayment::class;

    public function definition()
    {
        return [
            'invoice_id' => null,
            'amount' => $this->faker->randomFloat(2, 1, 2),
            'date' => $this->faker->date(),
            'reference' => $this->faker->bothify('REF-?#####'),
            'images' => json_encode([]),
            'description' => $this->faker->sentence(),
        ];
    }
}
