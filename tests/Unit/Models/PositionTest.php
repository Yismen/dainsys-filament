<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Position;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PositionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function position_model_interacts_with_positions_table()
    {
        $data = Position::factory()->make();

        Position::create($data->toArray());

        $this->assertDatabaseHas('positions', $data->only([
            'name',
            'department_id',
            'payment_type_id',
            // 'salary',
            'description',
        ]));
    }

    /** @test */
    public function position_model_uses_soft_delete()
    {
        $position = Position::factory()->create();

        $position->delete();

        $this->assertSoftDeleted(Position::class, $position->only(['id']));
    }

    /** @test */
    public function positions_model_has_many_employees()
    {
        $position = Position::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $position->employees());
    }

    /** @test */
    public function positions_model_belongs_to_department()
    {
        $position = Position::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $position->department());
    }

    /** @test */
    public function positions_model_belongs_to_paymentType()
    {
        $position = Position::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $position->paymentType());
    }
}
