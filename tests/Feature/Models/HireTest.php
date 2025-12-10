<?php

use App\Models\Hire;
use App\Events\HireCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



test('hires model interacts with db table', function () {
    Mail::fake();
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

// test('hires model belongs to employee', function () {
//     Mail::fake();
//     $hire = Hire::factory()->create();

//     expect($hire->employee)->toBeInstanceOf(\App\Models\Employee::class);
//     expect($hire->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
// });

// test('hire model fires event when created', function () {
//     Mail::fake();
//     Event::fake();
//     $hire = Hire::factory()->create();

//     Event::assertDispatched(EmployeeHired::class);
// });

// test('hires model belongs to site', function () {
//     Mail::fake();
//     $hire = Hire::factory()->create();

//     expect($hire->site)->toBeInstanceOf(\App\Models\Site::class);
//     expect($hire->site())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
// });

// test('hires model belongs to project', function () {
//     Mail::fake();
//     $hire = Hire::factory()->create();

//     expect($hire->project)->toBeInstanceOf(\App\Models\Project::class);
//     expect($hire->project())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
// });

// test('hires model belongs to position', function () {
//     Mail::fake();
//     $hire = Hire::factory()->create();

//     expect($hire->position)->toBeInstanceOf(\App\Models\Position::class);
//     expect($hire->position())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
// });

// test('hires model belongs to supervisor', function () {
//     Mail::fake();
//     $hire = Hire::factory()->create();

//     expect($hire->supervisor)->toBeInstanceOf(\App\Models\Supervisor::class);
//     expect($hire->supervisor())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
// });

/** @test */
// public function email_is_sent_when_hire_is_created()
// {
//     Mail::fake();
//     Hire::factory()->create();
//     Mail::assertQueued(MailHireCreated::class);
// }
