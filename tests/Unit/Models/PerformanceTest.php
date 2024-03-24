<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Performance;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function performance_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = Performance::factory()->make();

        Performance::create($data->toArray());

        $this->assertDatabaseHas('performances', $data->only([
            'unique_id', 'file', 'date', 'employee_id', 'campaign_id', 'campaign_goal', 'login_time', 'production_time', 'talk_time', 'billable_time', 'attempts', 'contacts', 'successes', 'upsales', 'revenue', 'downtime_reason_id', 'reporter_id',
        ]));
    }

    /** @test */
    public function performance_model_uses_soft_delete()
    {
        Mail::fake();
        $performance = Performance::factory()->create();

        $performance->delete();

        $this->assertSoftDeleted(Performance::class, $performance->only(['id']));
    }

    /** @test */
    public function performance_model_belongs_to_employee()
    {
        Mail::fake();
        $performance = Performance::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $performance->employee());
    }

    /** @test */
    public function performance_model_belongs_to_campaign()
    {
        Mail::fake();
        $performance = Performance::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $performance->campaign());
    }

    /** @test */
    public function performance_model_belongs_to_downtime_reason()
    {
        Mail::fake();
        $performance = Performance::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $performance->downtimeReason());
    }

    /** @test */
    public function performance_model_belongs_to_reporter()
    {
        Mail::fake();
        $performance = Performance::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $performance->reporter());
    }
}
