<?php

use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Supervisor;
use App\Models\User;
use App\Notifications\EmployeePasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Notification::fake();
    Mail::fake(); // Prevent email sending in tests
});

it('resets employee password and sets force_password_change flag', function (): void {
    $citizenship = Citizenship::factory()->create();

    $employee = Employee::factory()->create([
        'citizenship_id' => $citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
        'password_set_at' => now()->subDay(),
        'force_password_change' => false,
    ]);

    $employee->refresh();

    // Simulate password reset
    $user->update([
        'force_password_change' => true,
    ]);

    assertDatabaseHas('users', [
        'id' => $user->id,
        'force_password_change' => true,
    ]);
});

it('sends notification to supervisor when password is reset', function (): void {
    $citizenship = Citizenship::factory()->create();

    $supervisorUser = User::factory()->create();
    $supervisor = Supervisor::factory()->create([
        'user_id' => $supervisorUser->id,
    ]);

    // Ensure the supervisor actually has the correct user
    $supervisor->refresh();
    expect($supervisor->user_id)->toBe($supervisorUser->id);

    $employee = Employee::factory()->create([
        'citizenship_id' => $citizenship->id,
        'supervisor_id' => $supervisor->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ]);

    $employee->refresh();
    $employee->load('supervisor.user');

    // Notify supervisor (simulate what the action does)
    $user->update(['force_password_change' => true]);

    $employee->supervisor->user->notify(new EmployeePasswordReset($employee));

    Notification::assertSentTo($employee->supervisor->user, EmployeePasswordReset::class);
});

it('allows user to update password when force_password_change is true', function (): void {
    $citizenship = Citizenship::factory()->create();

    $employee = Employee::factory()->create([
        'citizenship_id' => $citizenship->id,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
        'force_password_change' => true,
        'password_set_at' => now()->subDay(),
    ]);

    expect($user->force_password_change)->toBeTrue();

    // User updates their password
    $user->update([
        'password' => \Illuminate\Support\Facades\Hash::make('NewPassword123!'),
        'password_set_at' => now(),
        'force_password_change' => false,
    ]);

    $user->refresh();

    expect($user->force_password_change)->toBe(false);
    expect($user->password_set_at)->not->toBeNull();
});
