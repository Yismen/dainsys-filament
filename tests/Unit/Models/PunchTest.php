<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Punch;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PunchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function punches_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = Punch::factory()->make();

        Punch::create($data->toArray());

        $this->assertDatabaseHas('punches', $data->only([
            'punch', 'employee_id', 'deleted_at'
        ]));
    }

    /** @test */
    public function punches_model_belongs_to_one_employee()
    {
        Mail::fake();
        $punch = Punch::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $punch->employee());
    }
}
