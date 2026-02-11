<?php

use App\Filament\Employee\Pages\SelfProfile;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses()->group('employee-panel');

beforeEach(function (): void {
    Mail::fake();

    $this->citizenship = Citizenship::factory()->create();
});

it('displays employee profile for authenticated employee', function (): void {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
        'full_name' => 'John Doe',
        'personal_id' => '12345678901',
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subDays(10),
    ]);

    $employee->refresh();

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ]);

    $this->actingAs($user);

    $response = $this->get(SelfProfile::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('Personal Information')
        ->assertSee('Contact Information')
        ->assertSee('Current Employment');
});

it('prevents access for users without employee_id', function (): void {
    $user = User::factory()->create([
        'employee_id' => null,
    ]);

    $this->actingAs($user);

    $this->get(SelfProfile::getUrl(panel: 'employee'))
        ->assertForbidden();
});

it('displays employee hires history', function (): void {
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

    $this->actingAs($user);

    $this->get(SelfProfile::getUrl(panel: 'employee'))
        ->assertSuccessful()
        ->assertSee('Employment History');
});
