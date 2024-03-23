<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\DowntimeReason;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DowntimeReasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function downtime_reasons_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = DowntimeReason::factory()->make();

        DowntimeReason::create($data->toArray());

        $this->assertDatabaseHas('downtime_reasons', $data->only([
            'name'
        ]));
    }

    /** @test */
    public function downtime_reason_model_uses_soft_delete()
    {
        Mail::fake();
        $downtime_reason = DowntimeReason::factory()->create();

        $downtime_reason->delete();

        $this->assertSoftDeleted(DowntimeReason::class, $downtime_reason->only(['id']));
    }

    /** @test */
    // public function downtime_reasons_model_has_many_performances()
    // {
    //     Mail::fake();
    //     $downtime_reason = DowntimeReason::factory()->create();

    //     $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $downtime_reason->performances());
    // }
}
