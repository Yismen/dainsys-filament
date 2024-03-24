<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HolidayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function holidays_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = Holiday::factory()->make();

        Holiday::create($data->toArray());

        $this->assertDatabaseHas('holidays', $data->only([
            'name', 'date', 'description'
        ]));
    }

    /** @test */
    public function holiday_model_uses_soft_delete()
    {
        Mail::fake();
        $holiday = Holiday::factory()->create();

        $holiday->delete();

        $this->assertSoftDeleted(Holiday::class, $holiday->only(['id']));
    }
}
