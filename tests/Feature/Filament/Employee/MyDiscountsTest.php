<?php

use App\Filament\Employee\Pages\MyDeductions;
use App\Models\Citizenship;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses()->group('employee-panel');

beforeEach(function (): void {
    Mail::fake();
    $this->citizenship = Citizenship::factory()->create();
});

it('displays deductionss data for authenticated employee', function (): void {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subMonths(3),
    ]);

    $employee->refresh();

    Deduction::factory()->count(3)->create(['employee_id' => $employee->id]);
    $otherEmployee = Employee::factory()->create(['citizenship_id' => $this->citizenship->id]);
    Deduction::factory()->count(2)->create(['employee_id' => $otherEmployee->id]);

    $user = User::factory()->create(['employee_id' => $employee->id]);

    $this->actingAs($user);

    $response = $this->get(MyDeductions::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('My Deductions');

    $response->assertSee('Showing 1 to 3 of 3');
});

it('prevents access for users without employee_id', function (): void {
    $user = User::factory()->create([
        'employee_id' => null,
    ]);

    $this->actingAs($user);

    $this->get(MyDeductions::getUrl(panel: 'employee'))
        ->assertForbidden();
});

it('only shows deductionss data for authenticated employee', function (): void {
    $employee1 = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee1->id,
        'date' => now()->subMonths(3),
    ]);

    $employee1->refresh();

    $employee2 = Employee::factory()->create(['citizenship_id' => $this->citizenship->id]);

    Deduction::factory()->count(2)->create(['employee_id' => $employee1->id]);
    Deduction::factory()->count(2)->create(['employee_id' => $employee2->id]);

    $user = User::factory()->create(['employee_id' => $employee1->id]);

    $this->actingAs($user);

    $response = $this->get(MyDeductions::getUrl(panel: 'employee'));

    $response->assertSuccessful();
    $response->assertSee('Showing 1 to 2 of 2');
});

// it('allows filtering deductionss by payable date', function (): void {
//     $employee = Employee::factory()->create([
//         'citizenship_id' => $this->citizenship->id,
//     ]);

//     Hire::factory()->create([
//         'employee_id' => $employee->id,
//         'date' => now()->subMonths(3),
//     ]);

//     $employee->refresh();

//     $deduction1 = Deduction::factory()->create([
//         'employee_id' => $employee->id,
//         'payable_date' => now()->addDays(5),
//     ]);

//     $deduction2 = Deduction::factory()->create([
//         'employee_id' => $employee->id,
//         'payable_date' => now()->addDays(10),
//     ]);

//     $user = User::factory()->create(['employee_id' => $employee->id]);

//     $this->actingAs($user);

//     Livewire::test(MyDeductions::class)
//         ->filterTable('payable_date', now()->addDays(5)->toDateString())
//         ->assertCanSeeTableRecords([$deduction1])
//         ->assertCanNotSeeTableRecords([$deduction2]);
// });
