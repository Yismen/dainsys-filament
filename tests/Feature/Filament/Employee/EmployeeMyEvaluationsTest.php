<?php

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Filament\Employee\Pages\MyEvaluations;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\QAForm;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function createEmployeeEvaluationScenario(): array
{
    Filament::setCurrentPanel(Filament::getPanel('employee'));

    Role::firstOrCreate(['name' => QARoles::Manager->value], ['guard_name' => 'web']);
    Role::firstOrCreate(['name' => QARoles::Agent->value], ['guard_name' => 'web']);

    $qaManager = User::factory()->create();
    $qaManager->assignRole(QARoles::Manager->value);

    $qaAgent = User::factory()->create();
    $qaAgent->assignRole(QARoles::Agent->value);

    $supervisorUser = User::factory()->create();
    $supervisor = Supervisor::factory()->create([
        'user_id' => $supervisorUser->id,
    ]);

    $employee = Employee::factory()->hired()->create([
        'supervisor_id' => $supervisor->id,
    ]);

    $employeeUser = User::factory()->create([
        'employee_id' => $employee->id,
    ]);

    $qaForm = QAForm::factory()->create([
        'created_by' => $qaManager->id,
    ]);

    return compact('qaManager', 'qaAgent', 'employee', 'employeeUser', 'qaForm');
}

it('allows employee to accept own published evaluation from my evaluations page', function (): void {
    $scenario = createEmployeeEvaluationScenario();

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['qaAgent']->id,
        'qa_form_id' => $scenario['qaForm']->id,
        'threshold_percentage' => 80,
        'status' => EvaluationStatuses::Published,
    ]);

    actingAs($scenario['employeeUser']);

    livewire(MyEvaluations::class)
        ->callAction('accept', $evaluation, [
            'comment' => 'Employee accepts evaluation.',
        ]);

    $evaluation->refresh();

    expect($evaluation->status)->toBe(EvaluationStatuses::AcceptedClosed)
        ->and($evaluation->accepted_at)->not->toBeNull();
});

it('allows employee to dispute own published evaluation from my evaluations page', function (): void {
    $scenario = createEmployeeEvaluationScenario();

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['qaAgent']->id,
        'qa_form_id' => $scenario['qaForm']->id,
        'threshold_percentage' => 80,
        'status' => EvaluationStatuses::Published,
    ]);

    actingAs($scenario['employeeUser']);

    livewire(MyEvaluations::class)
        ->callAction('dispute', $evaluation, [
            'comment' => 'Employee disputes result.',
        ]);

    $evaluation->refresh();

    expect($evaluation->status)->toBe(EvaluationStatuses::Disputed)
        ->and($evaluation->employee_decision_comment)->toBe('Employee disputes result.');
});
