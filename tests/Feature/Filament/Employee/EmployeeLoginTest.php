<?php

use App\Enums\EmployeeStatuses;
use App\Filament\Employee\Pages\Login;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Termination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Mail::fake(); // Prevent email sending in tests
    $this->citizenship = Citizenship::factory()->create();
});

it('allows hired employee to log in with personal id and password', function (): void {
    $employee = Employee::factory()->create([
        'personal_id' => '12345678901',
        'internal_id' => 'EMP001',
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    expect($employee->status)->toBe(EmployeeStatuses::Hired);

    Livewire::test(Login::class)
        ->fillForm([
            'personalId' => '12345678901',
        ])
        ->call('authenticate');

    // Should show password form for new user
    expect(Livewire::test(Login::class)->get('showPasswordForm'))->toBeFalse();
});

it('allows suspended employee to log in', function (): void {
    $employee = Employee::factory()->create([
        'personal_id' => '12345678902',
        'internal_id' => 'EMP002',
        'status' => EmployeeStatuses::Suspended,
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    Livewire::test(Login::class)
        ->fillForm([
            'personalId' => '12345678902',
        ])
        ->call('authenticate');

    // Should proceed since employee is suspended but still active
});

it('prevents terminated employee from logging in', function (): void {
    $employee = Employee::factory()->create([
        'personal_id' => '12345678903',
        'internal_id' => 'EMP003',
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    Termination::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDay(),
    ]);

    $employee->refresh();

    expect($employee->status)->toBe(EmployeeStatuses::Terminated);

    Livewire::test(Login::class)
        ->fillForm([
            'personalId' => '12345678903',
        ])
        ->call('authenticate')
        ->assertHasErrors(['data.personalId']);
});

it('creates user account on first login when password is set', function (): void {
    $employee = Employee::factory()->create([
        'personal_id' => '12345678904',
        'internal_id' => 'EMP004',
        'citizenship_id' => $this->citizenship->id,
        'email' => 'test@example.com',
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    // First step - validate credentials and create password
    $component = Livewire::test(Login::class)
        ->fillForm([
            'personalId' => '12345678904',
        ])
        ->call('authenticate')
        ->assertSet('showPasswordForm', true)
        ->assertSet('employeeId', $employee->id);

    // Second step - set password
    $component
        ->fillForm([
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect();

    // User should be created
    assertDatabaseHas('users', [
        'employee_id' => $employee->id,
        'email' => 'test@example.com',
    ]);

    $user = User::where('employee_id', $employee->id)->first();
    expect($user)->not->toBeNull();
    expect(Hash::check('NewPassword123!', $user->password))->toBeTrue();
    expect($user->password_set_at)->not->toBeNull();
    expect($user->force_password_change)->toBeFalse();
    expect($user->is_active)->toBeTrue();
});

it('authenticates existing user with correct password', function (): void {
    $employee = Employee::factory()->create([
        'personal_id' => '12345678905',
        'internal_id' => 'EMP005',
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    $user = User::factory()->create([
        'employee_id' => $employee->id,
        'password' => Hash::make('ExistingPassword123!'),
        'password_set_at' => now()->subDay(),
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'personalId' => '12345678905',
            'password' => 'ExistingPassword123!',
        ])
        ->call('authenticate')
        ->assertHasNoErrors();
});

it('rejects invalid credentials', function (): void {
    Livewire::test(Login::class)
        ->fillForm([
            'personalId' => '99999999999',
        ])
        ->call('authenticate')
        ->assertHasErrors(['data.personalId']);
});

it('prevents employee panel access for users without employee_id', function (): void {
    $user = User::factory()->create([
        'employee_id' => null,
    ]);

    $this->actingAs($user);

    $panel = filament()->getPanel('employee');

    expect($user->canAccessPanel($panel))->toBeFalse();
});

it('allows employee panel access for hired employees', function (): void {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ]);

    $panel = filament()->getPanel('employee');

    expect($user->canAccessPanel($panel))->toBeTrue();
});
