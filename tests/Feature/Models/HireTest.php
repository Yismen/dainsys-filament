<?php

use App\Events\EmployeeHiredEvent;
use App\Models\Hire;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);
});

test('hires model interacts with db table', function () {
    $data = Hire::factory()->make();

    Hire::create($data->toArray());

    $this->assertDatabaseHas('hires', $data->only([
        'employee_id',
        'date',
        'site_id',
        'project_id',
        'position_id',
        'supervisor_id',
        'punch',
    ]));
});

test('social_securities model belongs to model', function (string $modelClass, string $relationMethod) {
    $hire = Hire::factory()->create();

    expect($hire->$relationMethod)->toBeInstanceOf($modelClass);
    expect($hire->$relationMethod())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    [\App\Models\Employee::class, 'employee'],
    [\App\Models\Site::class, 'site'],
    [\App\Models\Project::class, 'project'],
    [\App\Models\Position::class, 'position'],
    [\App\Models\Supervisor::class, 'supervisor'],
]);

it('fires event when a employee hire is created', function () {
    $hire = Hire::factory()->create();

    Event::assertDispatched(EmployeeHiredEvent::class);
});
