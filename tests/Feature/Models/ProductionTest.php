<?php

use App\Models\Campaign;
use App\Enums\RevenueTypes;
use App\Models\Production;
use Illuminate\Support\Facades\Mail;

test('performance model interacts with db table', function () {
    Mail::fake();
    $data = Production::factory()->make();

    Production::create($data->toArray());

    $this->assertDatabaseHas('productions', $data->only([
        'unique_id', 'date', 'employee_id', 'revenue_type_id', 'campaign_id', 'campaign_goal', 'conversions', 'total_time', 'production_time', 'billable_time', 'revenue'
    ]));
});

test('performance model uses soft delete', function () {
    Mail::fake();
    $performance = Production::factory()->create();

    $performance->delete();

    $this->assertSoftDeleted(Production::class, [
        'id' => $performance->id
    ]);
});

test('performance model belongs to revenue type', function () {
    Mail::fake();
    $performance = Production::factory()->create();

    expect($performance->revenueType)->toBeInstanceOf(\App\Models\RevenueType::class);
    expect($performance->revenueType())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('performance revenue type is nullable by default', function () {
    Mail::fake();
    $performance = Production::factory()->create(['revenue_type_id' => null]);

    expect($performance->revenueType)->toBeNull();
});

test('revenue type updates when it is created or when the campaign is changed', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create();
    $performance = Production::factory()->create(['campaign_id' => $campaign->id, 'revenue_type_id' => null]);

    expect($performance->revenueType)->toBeInstanceOf(\App\Models\RevenueType::class);

    $new_campaign = Campaign::factory()->create();
    $performance->update(['campaign_id' => $new_campaign->id]);

    expect($performance->revenueType)->toBeInstanceOf(\App\Models\RevenueType::class);
});

test('performance model belongs to employee', function () {
    Mail::fake();
    $performance = Production::factory()->create();

    expect($performance->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($performance->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('performance model belongs to campaign', function () {
    Mail::fake();
    $performance = Production::factory()->create();

    expect($performance->campaign)->toBeInstanceOf(\App\Models\Campaign::class);
    expect($performance->campaign())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('billable time and revenuve are updated when revenue type is login time', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'rate' => 5]);
    $performance = Production::factory()->create(['campaign_id' => $campaign->id, 'login_time' => 5, 'revenue' => 1000000]);

    $performance->update(['login_time' => 10]);

    expect($performance->revenue)->toEqual(50);
    // = campaign rate * login time
    expect($performance->billable_time)->toEqual(10);
    // = login time
    $this->assertDatabaseHas(Production::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated when revenue type is production time', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::ProductionTime, 'rate' => 5]);
    $performance = Production::factory()->create(['campaign_id' => $campaign->id, 'production_time' => 5, 'revenue' => 1000000]);

    $performance->update(['production_time' => 10]);

    expect($performance->revenue)->toEqual(50);
    // = campaign rate * production time
    expect($performance->billable_time)->toEqual(10);
    // = production time
    $this->assertDatabaseHas(Production::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated when revenue type is talk time', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::TalkTime, 'rate' => 5]);
    $performance = Production::factory()->create(['campaign_id' => $campaign->id, 'talk_time' => 5, 'revenue' => 1000000]);

    $performance->update(['talk_time' => 10]);

    expect($performance->revenue)->toEqual(50);
    // = campaign rate * talk time
    expect($performance->billable_time)->toEqual(10);
    // = talk time
    $this->assertDatabaseHas(Production::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated when revenue type is sales', function () {
    Mail::fake();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Sales, 'rate' => 5]);
    $performance = Production::factory()->create(['campaign_id' => $campaign->id, 'successes' => 5, 'revenue' => 1000000]);

    $performance->update(['successes' => 10, 'production_time' => 50]);

    expect($performance->revenue)->toEqual(10 * $campaign->rate);
    // = campaign rate * success
    expect($performance->billable_time)->toEqual(50);
    // = production time
    $this->assertDatabaseHas(Production::class, [
        'id' => $performance->id,
        'revenue' => 50,
        'billable_time' => 50,
    ]);
});
