<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\OvernightHour;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OvernightHoursTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function overnight_hours_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = OvernightHour::factory()->make();

        OvernightHour::create($data->toArray());

        $this->assertDatabaseHas('overnight_hours', $data->only([
            'date', 'employee_id', 'hours'
        ]));
    }

    /** @test */
    // public function overnight_hour_model_uses_soft_delete()
    // {
    //     Mail::fake();
    //     $overnight_hour = OvernightHour::factory()->create();

    //     $overnight_hour->delete();

    //     $this->assertSoftDeleted(OvernightHour::class, $overnight_hour->only(['id']));
    // }

    /** @test */
    public function overnight_hours_model_belongs_to_employee()
    {
        Mail::fake();
        $overnight_hour = OvernightHour::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $overnight_hour->employee());
    }
}
