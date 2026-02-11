<?php

use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\User;

beforeEach(function (): void {
    \Illuminate\Support\Facades\Event::fake([
        \App\Events\EmployeeHiredEvent::class,
    ]);
});

test('supervisors model interacts with db table', function (): void {
    $data = Supervisor::factory()->make();

    Supervisor::create($data->toArray());

    $this->assertDatabaseHas('supervisors', $data->only([
        'name', 'description', 'user_id', 'is_active',
    ]));
});

test('supervisors model has many hires', function (): void {
    $supervisor = Supervisor::factory()
        ->hasHires()
        ->create();

    expect($supervisor->hires->first())->toBeInstanceOf(\App\Models\Hire::class);
    expect($supervisor->hires())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('supervisor model has many employees', function (): void {
    $employee = Employee::factory()->create();
    $supervisor = Supervisor::factory()
        ->hasHires(1, [
            'employee_id' => $employee->id,
        ])
        ->create();

    expect($supervisor->employees->first())->toBeInstanceOf(Employee::class);
    expect($supervisor->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('supervisor model belongs to user', function (): void {
    $supervisor = Supervisor::factory()->create();

    expect($supervisor->user)->toBeInstanceOf(User::class);
    expect($supervisor->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('apply global scope active supervisors', function (): void {
    Supervisor::factory()->count(3)->create(['is_active' => true]);
    Supervisor::factory()->count(2)->create(['is_active' => false]);

    expect(Supervisor::all())->toHaveCount(3);
});
