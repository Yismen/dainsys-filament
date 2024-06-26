<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PaymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function payment_types_model_interacts_with_db_table()
    {
        $data = PaymentType::factory()->make();

        PaymentType::create($data->toArray());

        $this->assertDatabaseHas('payment_types', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function payment_type_model_uses_soft_delete()
    {
        $payment_type = PaymentType::factory()->create();

        $payment_type->delete();

        $this->assertSoftDeleted(PaymentType::class, $payment_type->only(['id']));
    }

    /** @test */
    public function payment_types_model_has_many_positions()
    {
        $payment_type = PaymentType::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $payment_type->positions());
    }
}
