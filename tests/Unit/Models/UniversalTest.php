<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Universal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UniversalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function universals_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = Universal::factory()->make();

        Universal::create($data->toArray());

        $this->assertDatabaseHas('universals', $data->only([
            'employee_id', 'date_since', 'comments'
        ]));
    }

    /** @test */
    public function universal_model_uses_soft_delete()
    {
        Mail::fake();
        $universal = Universal::factory()->create();

        $universal->delete();

        $this->assertSoftDeleted(Universal::class, $universal->only(['id']));
    }

    /** @test */
    public function universals_model_belongs_to_employee()
    {
        Mail::fake();
        $universal = Universal::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $universal->employee());
    }
}
