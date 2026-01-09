<?php

use App\Models\User;
use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\Employee;
use App\Enums\RevenueTypes;
use App\Models\DowntimeReason;
use Illuminate\Support\Carbon;
use App\Exceptions\InvalidDowntimeCampaign;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('downtime model interacts with db table', function () {
    $data = Downtime::factory()->make();

    Downtime::create($data->toArray());

    $this->assertDatabaseHas('downtimes', $data->only([
        // 'date',
        'employee_id',
        'campaign_id',
        'downtime_reason_id',
        'time',
        // 'requester_id',
        // 'aprover_id',
        'converted_to_payroll_at',
    ]));
});

test('downtime model belongs to employee', function () {
    $downtime = Downtime::factory()
        ->has(Employee::factory(), 'employee')
        ->create();

    expect($downtime->employee)->toBeInstanceOf(Employee::class);
    expect($downtime->employee())->toBeInstanceOf(BelongsTo::class);
});

test('downtime model belongs to campaign', function () {
    $downtime = Downtime::factory()
        ->has(Campaign::factory(), 'campaign')
        ->create();

    expect($downtime->campaign)->toBeInstanceOf(Campaign::class);
    expect($downtime->campaign())->toBeInstanceOf(BelongsTo::class);
});

test('downtime model belongs to downtime reason', function () {
    $downtime = Downtime::factory()
        ->has(DowntimeReason::factory(), 'downtimeReason')
        ->create();

    expect($downtime->downtimeReason)->toBeInstanceOf(DowntimeReason::class);
    expect($downtime->downtimeReason())->toBeInstanceOf(BelongsTo::class);
});

test('downtime model belongs to requester', function () {
    $this->actingAs(User::factory()->create());

    $downtime = Downtime::factory()
        ->create();

    expect($downtime->requester)->toBeInstanceOf(User::class);
    expect($downtime->requester())->toBeInstanceOf(BelongsTo::class);
});

test('downtime model belongs to aprover', function () {
    $downtime = Downtime::factory()
        ->create();

    $this->actingAs(User::factory()->create());

    $downtime->aprove();

    expect($downtime->aprover)->toBeInstanceOf(User::class);
    expect($downtime->aprover())->toBeInstanceOf(BelongsTo::class);
});

test('date is instance of Date', function () {
    $downtime = Downtime::factory()
        ->create();

    expect($downtime->date)->toBeInstanceOf(Carbon::class);
});

it('calculates unique_id field', function () {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $date = now();
    $downtime = Downtime::factory()
        ->for($employee)
        ->for($campaign)
        ->create([
            'date' => $date
        ]);

    expect($downtime->unique_id)
        ->toBe(join('_', [
            $date->format('Y-m-d'),
            $campaign->id,
            $employee->id,
        ]));
});

it('throws exception if campaign revenue type is not downtime', function () {
   Downtime::factory()
        ->for(Campaign::factory(state: ['revenue_type' => RevenueTypes::LoginTime]))
        ->create();
})->throws(InvalidDowntimeCampaign::class);
