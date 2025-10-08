<?php

use App\Models\Campaign;
use App\Enums\RevenueTypes;
use App\Models\Performance;
use Illuminate\Support\Facades\Mail;

test('performance model interacts with db table', function () {
    Mail::fake();
    $data = Performance::factory()->make();

    Performance::create($data->toArray());

    $this->assertDatabaseHas('performances', $data->only([
        'file', 'date', 'employee_id', 'campaign_id', 'campaign_goal', 'login_time', 'production_time', 'talk_time', 'attempts', 'contacts', 'successes', 'upsales',  'downtime_reason_id', 'reporter_id',
    ]));
});

test('performance model uses soft delete', function () {
    Mail::fake();
    $performance = Performance::factory()->create();

    $performance->delete();

    $this->assertSoftDeleted(Performance::class, [
        'id' => $performance->id
    ]);
});

test('performance model belongs to employee', function () {
    Mail::fake();
    $performance = Performance::factory()->create();

    expect($performance->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('performance model belongs to campaign', function () {
    Mail::fake();
    $performance = Performance::factory()->create();

    expect($performance->campaign())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('performance model belongs to downtime reason', function () {
    Mail::fake();
    $performance = Performance::factory()->create();

    expect($performance->downtimeReason())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('performance model belongs to reporter', function () {
    Mail::fake();
    $performance = Performance::factory()->create();

    expect($performance->reporter())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('billable time and revenuve are updated when revenue type is login time', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'rate' => 5]);
    $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'login_time' => 5, 'revenue' => 1000000]);

    $performance->update(['login_time' => 10]);

    expect($performance->revenue)->toEqual(50);
    // = campaign rate * login time
    expect($performance->billable_time)->toEqual(10);
    // = login time
    $this->assertDatabaseHas(Performance::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated when revenue type is production time', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::ProductionTime, 'rate' => 5]);
    $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'production_time' => 5, 'revenue' => 1000000]);

    $performance->update(['production_time' => 10]);

    expect($performance->revenue)->toEqual(50);
    // = campaign rate * production time
    expect($performance->billable_time)->toEqual(10);
    // = production time
    $this->assertDatabaseHas(Performance::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated when revenue type is talk time', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::TalkTime, 'rate' => 5]);
    $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'talk_time' => 5, 'revenue' => 1000000]);

    $performance->update(['talk_time' => 10]);

    expect($performance->revenue)->toEqual(50);
    // = campaign rate * talk time
    expect($performance->billable_time)->toEqual(10);
    // = talk time
    $this->assertDatabaseHas(Performance::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated when revenue type is sales', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Sales, 'rate' => 5]);
    $performance = Performance::factory()->create(['campaign_id' => $campaign->id, 'successes' => 5, 'revenue' => 1000000]);

    $performance->update(['successes' => 10, 'production_time' => 50]);

    expect($performance->revenue)->toEqual(10 * $campaign->rate);
    // = campaign rate * success
    expect($performance->billable_time)->toEqual(50);
    // = production time
    $this->assertDatabaseHas(Performance::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 50,
    ]);
});
