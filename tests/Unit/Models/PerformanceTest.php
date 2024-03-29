<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Campaign;
use App\Enums\RevenueTypes;
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
            'file', 'date', 'employee_id', 'campaign_id', 'campaign_goal', 'login_time', 'production_time', 'talk_time', 'attempts', 'contacts', 'successes', 'upsales',  'downtime_reason_id', 'reporter_id',
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

    /** @test */
    public function billable_time_and_revenuve_are_updated_when_revenue_type_is_login_time()
    {
        Mail::fake();
        $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'rate' => 5]);
        $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'login_time' => 5, 'revenue' => 1000000]);

        $performance->update(['login_time' => 10]);

        $this->assertEquals(50, $performance->revenue); // = campaign rate * login time
        $this->assertEquals(10, $performance->billable_time); // = login time
        $this->assertDatabaseHas(Performance::class, [
            'id' => $performance->id,
            'revenue' => 50,
            'billable_time' => 10,
        ]);
    }

    /** @test */
    public function billable_time_and_revenuve_are_updated_when_revenue_type_is_production_time()
    {
        Mail::fake();
        $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::ProductionTime, 'rate' => 5]);
        $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'production_time' => 5, 'revenue' => 1000000]);

        $performance->update(['production_time' => 10]);

        $this->assertEquals(50, $performance->revenue); // = campaign rate * production time
        $this->assertEquals(10, $performance->billable_time); // = production time
        $this->assertDatabaseHas(Performance::class, [
            'id' => $performance->id,
            'revenue' => 50,
            'billable_time' => 10,
        ]);
    }

    /** @test */
    public function billable_time_and_revenuve_are_updated_when_revenue_type_is_talk_time()
    {
        Mail::fake();
        $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::TalkTime, 'rate' => 5]);
        $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'talk_time' => 5, 'revenue' => 1000000]);

        $performance->update(['talk_time' => 10]);

        $this->assertEquals(50, $performance->revenue); // = campaign rate * talk time
        $this->assertEquals(10, $performance->billable_time); // = talk time
        $this->assertDatabaseHas(Performance::class, [
            'id' => $performance->id,
            'revenue' => 50,
            'billable_time' => 10,
        ]);
    }

    /** @test */
    public function billable_time_and_revenuve_are_updated_when_revenue_type_is_sales()
    {
        Mail::fake();
        $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Sales, 'rate' => 5]);
        $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'successes' => 5, 'revenue' => 1000000]);

        $performance->update(['successes' => 10, 'production_time' => 50]);

        $this->assertEquals(10 * $campaign->rate, $performance->revenue); // = campaign rate * success
        $this->assertEquals(50, $performance->billable_time); // = production time
        $this->assertDatabaseHas(Performance::class, [
            'id' => $performance->id,
            'revenue' => 50,
            'billable_time' => 50,
        ]);
    }
}
