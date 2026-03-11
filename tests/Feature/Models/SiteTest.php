<?php

use App\Events\EmployeeHiredEvent;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Site;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
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

    expect($site->hires->first())->toBeInstanceOf(Hire::class);
    expect($site->hires())->toBeInstanceOf(HasMany::class);
});

test('site model has many employees', function (): void {
    $employee = Employee::factory()->create();
    $site = Site::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($site->employees->first())->toBeInstanceOf(Employee::class);
    expect($site->employees())->toBeInstanceOf(HasMany::class);
});
