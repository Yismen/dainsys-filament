<?php

use App\Models\Employee;
use App\Models\Site;

beforeEach(function (): void {
    \Illuminate\Support\Facades\Event::fake([
        \App\Events\EmployeeHiredEvent::class,
    ]);
});

test('sites model interacts with db table', function (): void {
    $data = Site::factory()->make();

    Site::create($data->toArray());

    $this->assertDatabaseHas('sites', $data->only([
        'name', 'person_of_contact', 'phone', 'email', 'address', 'geolocation', 'description',
    ]));
});

test('sites model has many hires', function (): void {
    $site = Site::factory()
        ->hasHires()
        ->create();

    expect($site->hires->first())->toBeInstanceOf(\App\Models\Hire::class);
    expect($site->hires())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('site model has many employees', function (): void {
    $employee = Employee::factory()->create();
    $site = Site::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($site->employees->first())->toBeInstanceOf(Employee::class);
    expect($site->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
