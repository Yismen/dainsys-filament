<?php

use App\Enums\RevenueTypes;
use App\Events\EmployeeHiredEvent;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Project;
use App\Models\Supervisor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);
});

test('production model interacts with db table', function () {
    $data = Production::factory()->make();

    Production::create($data->toArray());

    $this->assertDatabaseHas('productions', $data->only([
        // 'unique_id',
        // 'date',
        'employee_id',
        'campaign_id',
        // 'revenue_type',
        // 'revenue_rate',
        // 'supervisor_id',
        // 'sph_goal',
        'conversions',
        'total_time',
        'production_time',
        'talk_time',
        // 'billable_time',
        // 'revenue',
        'converted_to_payroll_at',
    ]));
});

it('casts revenue type as enum', function () {
    $production = Production::factory()->create([
        'revenue_type' => RevenueTypes::LoginTime,
    ]);

    expect($production->revenue_type)->toBeInstanceOf(RevenueTypes::class);
    expect($production->revenue_type)->toBe(RevenueTypes::LoginTime);
});

it('casts revenue as money', function () {

    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'revenue_rate' => 5]);
    $production = Production::factory()->create(['campaign_id' => $campaign->id, 'total_time' => 5]);

    expect($production->revenue)->toBe(expected: 25.0);
    $this->assertDatabaseHas('productions', [
        'id' => $production->id,
        'revenue' => 2500,
    ]);

});

test('production model belongs to relationship', function (string $modelClass, string $relationship) {
    $production = Production::factory()
        ->create();

    $production->revenue_type = RevenueTypes::LoginTime;
    $production->supervisor_id = Supervisor::factory()->create()->id;

    $production->save();

    expect($production->$relationship)->toBeInstanceOf($modelClass);
    expect($production->$relationship())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    [Employee::class, 'employee'],
    [Campaign::class, 'campaign'],
    [Supervisor::class, 'supervisor'],
]);

it('belongs to project thru campaign', function () {
    $production = Production::factory()
        ->create();

    expect($production->project())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOneThrough::class);
    expect($production->project)->toBeInstanceOf(Project::class);
});

it('updates revenue type and revenue rate and sph_goal based on the campaign when created', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'revenue_rate' => 5, 'sph_goal' => 1000]);
    $production = Production::factory()->create(['campaign_id' => $campaign->id]);

    expect($production->revenue_type)->toBe($campaign->revenue_type);
    expect((float) $production->revenue_rate)->toBe((float) $campaign->revenue_rate);
    expect((float) $production->sph_goal)->toBe((float) $campaign->sph_goal);
    $this->assertDatabaseHas('productions', [
        'id' => $production->id,
        'revenue_type' => RevenueTypes::LoginTime->value,
        'revenue_rate' => 5,
        'sph_goal' => 1000,
    ]);
});

it('updates revenue type and revenue rate and sph_goal based on the campaign when the campaign is updated', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'revenue_rate' => 5, 'sph_goal' => 1000]);
    $campaign_2 = Campaign::factory()->create(['revenue_type' => RevenueTypes::TalkTime, 'revenue_rate' => 20, 'sph_goal' => 30]);

    $production = Production::factory()->create(['campaign_id' => $campaign->id]);

    $production->update(['campaign_id' => $campaign_2->id]);

    expect($production->revenue_type)->toBe($campaign_2->revenue_type);
    expect((float) $production->revenue_rate)->toBe((float) $campaign_2->revenue_rate);
    expect((float) $production->sph_goal)->toBe((float) $campaign_2->sph_goal);
    $this->assertDatabaseHas('productions', [
        'id' => $production->id,
        'revenue_type' => RevenueTypes::TalkTime->value,
        'revenue_rate' => 20,
        'sph_goal' => 30,
    ]);
});

it('keeps revenue type and revenue rate and sph_goal if any other field that is not campaign_id is updated', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'revenue_rate' => 5, 'sph_goal' => 5]);

    $production = Production::factory()->create(['campaign_id' => $campaign->id]);

    $campaign->update(['revenue_type' => RevenueTypes::TalkTime, 'revenue_rate' => 10, 'sph_goal' => 10]);

    $production->update(['employee_id' => Employee::factory()->create()->id]);

    expect($production->revenue_type)->toBe(RevenueTypes::LoginTime);
    expect((float) $production->revenue_rate)->toBe((float) 5);
    expect((float) $production->sph_goal)->toBe((float) 5);
    $this->assertDatabaseHas('productions', [
        'id' => $production->id,
        'revenue_type' => RevenueTypes::LoginTime->value,
        'revenue_rate' => 5,
        'sph_goal' => 5,
    ]);
});

it('updates supervisor id based on employee when created', function () {
    $supervisor = Supervisor::factory()->create();
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->for($supervisor)->create();
    $production = Production::factory()
        ->create([
            'employee_id' => $employee->id,
            // 'supervisor_id' => null,
        ]);

    expect($production->supervisor_id)->toBe($production->employee->supervisor->id);
});

it('updates supervisor id based on employee when employee is changed', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisor2 = Supervisor::factory()->create();
    $employee = Employee::factory()->create();
    $employee2 = Employee::factory()->create();
    Hire::factory()->for($employee)->for($supervisor)->create();
    Hire::factory()->for($employee2)->for($supervisor2)->create();
    $production = Production::factory([
        'employee_id' => $employee->id,
        'supervisor_id' => null,
    ])
        ->create();

    $production->update(['employee_id' => $employee2->id]);

    expect($production->supervisor_id)->toBe($employee2->supervisor->id);
});

it('keeps supervisor id if employee is not changed even if supervisor has changed for the employee', function () {
    $supervisor = Supervisor::factory()->create();
    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->for($supervisor)->create();
    $production = Production::factory([
        'employee_id' => $employee->id,
    ])
        ->create();

    // updated the supervisor for the employee
    $newSupervisor = Supervisor::factory()->create();
    $hire->update(['supervisor_id' => $newSupervisor->id]);

    $production->touch();

    // keep original supervisor because the employee was not changed
    expect($production->supervisor_id)->toBe($supervisor->id);
});

test('billable time and revenuve are updated properly when revenue type is login time', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::LoginTime, 'revenue_rate' => 5]);
    $production = Production::factory()->create(['campaign_id' => $campaign->id, 'total_time' => 5, 'revenue' => 0]);

    $production->update(['total_time' => 10]);

    expect($production->revenue)->toEqual(50);
    // = campaign rate * login time
    expect($production->billable_time)->toEqual(10);
    // = login time
    $this->assertDatabaseHas(Production::class, [
        'id' => $production->id,
        'revenue' => 5000,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated properly when revenue type is production time', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::ProductionTime, 'revenue_rate' => 5]);
    $production = Production::factory()->create(['campaign_id' => $campaign->id, 'production_time' => 5, 'revenue' => 1000000]);

    $production->update(['production_time' => 10]);

    expect($production->revenue)->toEqual(50);
    // = campaign rate * production time
    expect($production->billable_time)->toEqual(10);
    // = production time
    $this->assertDatabaseHas(Production::class, [
        'id' => $production->id,
        'revenue' => 5000,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated properly when revenue type is talk time', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::TalkTime, 'revenue_rate' => 5]);
    $production = Production::factory()->create(['campaign_id' => $campaign->id, 'talk_time' => 5, 'revenue' => 1000000]);

    $production->update(['talk_time' => 10]);

    expect($production->revenue)->toEqual(50);
    // = campaign rate * talk time
    expect($production->billable_time)->toEqual(10);
    // = talk time
    $this->assertDatabaseHas(Production::class, [
        'id' => $production->id,
        'revenue' => 5000,
        'billable_time' => 10,
    ]);
});

test('billable time and revenuve are updated properly when revenue type is sales', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Conversions, 'revenue_rate' => 5]);
    $production = Production::factory()->create(['campaign_id' => $campaign->id, 'conversions' => 5, 'revenue' => 1000000]);

    $production->update(['conversions' => 10, 'production_time' => 50]);

    expect($production->revenue)->toEqual(10 * $campaign->revenue_rate);
    // = campaign revenue_rate * success
    expect($production->billable_time)->toEqual(50);
    // = production time
    $this->assertDatabaseHas(Production::class, [
        'id' => $production->id,
        'revenue' => 5000,
        'billable_time' => 50,
    ]);
});

test('date is instance of Date', function () {
    $downtime = Production::factory()
        ->create();

    expect($downtime->date)->toBeInstanceOf(Carbon::class);
});

it('calculates unique_id field', function () {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();
    $date = now();
    $production = Production::factory()
        ->for($employee)
        ->for($campaign)
        ->create([
            'date' => $date,
        ]);

    expect($production->unique_id)
        ->toBe(implode('_', [
            $date->format('Y-m-d'),
            $campaign->id,
            $employee->id,
        ]));
});
