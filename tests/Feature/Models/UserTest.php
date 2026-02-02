<?php

use App\Events\EmployeeHiredEvent;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Mailable;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('users model interacts with db table', function () {
    $data = User::factory()->create();

    $this->assertDatabaseHas('users', $data->only([
        'name',
        'email',
        'email_verified_at',
        'remember_token',
    ]));
});

test('users model belongs to many mailables', function () {
    $user = User::factory()
        ->has(Mailable::factory(), 'mailables')
        ->create();

    expect($user->mailables())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($user->mailables->first())->toBeInstanceOf(Mailable::class);
});

test('users model has one supervisor', function () {
    $user = User::factory()->create();
    Supervisor::factory()->create(['user_id' => $user->id]);

    expect($user->supervisor)->toBeInstanceOf(Supervisor::class);
    expect($user->supervisor())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

it('users model has many employees through supervisor', function () {
    Event::fake([
        EmployeeHiredEvent::class,
    ]);
    $supervisorUser = User::factory()->create();
    $supervisor = Supervisor::factory()->create(['user_id' => $supervisorUser->id]);
    Hire::factory()->count(3)->create(['supervisor_id' => $supervisor->id]);

    expect($supervisorUser->employees)->toHaveCount(3);
    expect($supervisorUser->employees->first())->toBeInstanceOf(Employee::class);
    expect($supervisorUser->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});

it('apply global scope active users', function () {
    User::factory()->count(3)->create(['is_active' => true]);
    User::factory()->count(2)->create(['is_active' => false]);

    expect(User::all())->toHaveCount(3);
});
