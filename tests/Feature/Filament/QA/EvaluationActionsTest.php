<?php

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Filament\QA\Resources\Evaluations\Pages\CreateEvaluation;
use App\Filament\QA\Resources\Evaluations\Pages\ListEvaluations;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationQuestionScore;
use App\Models\QAForm;
use App\Models\QAQuestion;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function createQaEvaluationScenario(): array
{
    Filament::setCurrentPanel(Filament::getPanel('quality-assurance'));

    Role::firstOrCreate(['name' => QARoles::Manager->value], ['guard_name' => 'web']);
    Role::firstOrCreate(['name' => QARoles::Agent->value], ['guard_name' => 'web']);

    $manager = User::factory()->create();
    $manager->assignRole(QARoles::Manager->value);

    $agent = User::factory()->create();
    $agent->assignRole(QARoles::Agent->value);

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

    $form = QAForm::factory()->create([
        'created_by' => $manager->id,
    ]);

    $question = QAQuestion::factory()->create([
        'qa_form_id' => $form->id,
        'author_id' => $manager->id,
        'display_order' => 1,
        'max_points' => 10,
    ]);

    return compact('manager', 'agent', 'supervisorUser', 'supervisor', 'employee', 'employeeUser', 'form', 'question');
}

it('allows qa users to access qa panel', function (): void {
    $scenario = createQaEvaluationScenario();
    $qaPanel = filament()->getPanel('quality-assurance');

    expect($scenario['manager']->canAccessPanel($qaPanel))->toBeTrue();
    expect($scenario['agent']->canAccessPanel($qaPanel))->toBeTrue();

    /** @var User $other */
    $other = User::factory()->createOne();
    actingAs($other);

    expect($other->canAccessPanel($qaPanel))->toBeFalse();
});

it('preloads all questions for the selected qa form on the create page', function (): void {
    $scenario = createQaEvaluationScenario();

    $secondQuestion = QAQuestion::factory()->create([
        'qa_form_id' => $scenario['form']->id,
        'author_id' => $scenario['manager']->id,
        'display_order' => 2,
        'max_points' => 7,
    ]);

    actingAs($scenario['agent']);

    livewire(CreateEvaluation::class)
        ->fillForm([
            'qa_form_id' => $scenario['form']->id,
        ])
        ->assertSet('data.threshold_percentage', (float) $scenario['form']->passing_threshold_percentage)
        ->assertSet('data.questionScores', [
            [
                'qa_question_id' => $scenario['question']->id,
                'max_points_snapshot' => 10,
                'points_awarded' => null,
                'evaluator_note' => null,
            ],
            [
                'qa_question_id' => $secondQuestion->id,
                'max_points_snapshot' => 7,
                'points_awarded' => null,
                'evaluator_note' => null,
            ],
        ]);
});

it('allows qa agent to publish evaluations from the table action', function (): void {
    $scenario = createQaEvaluationScenario();

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['agent']->id,
        'qa_form_id' => $scenario['form']->id,
        'threshold_percentage' => 80,
    ]);

    EvaluationQuestionScore::query()->create([
        'evaluation_id' => $evaluation->id,
        'qa_question_id' => $scenario['question']->id,
        'points_awarded' => 80,
        'max_points_snapshot' => 10,
    ]);

    actingAs($scenario['agent']);

    livewire(ListEvaluations::class)
        ->callAction('publish', $evaluation, [
            'comment' => 'Publishing QA result.',
        ]);

    $evaluation->refresh();

    expect($evaluation->status)->toBe(EvaluationStatuses::Published)
        ->and($evaluation->published_at)->not->toBeNull();
});

it('allows qa manager to resolve disputed evaluations from table action', function (): void {
    $scenario = createQaEvaluationScenario();

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['agent']->id,
        'qa_form_id' => $scenario['form']->id,
        'threshold_percentage' => 80,
    ]);

    EvaluationQuestionScore::query()->create([
        'evaluation_id' => $evaluation->id,
        'qa_question_id' => $scenario['question']->id,
        'points_awarded' => 60,
        'max_points_snapshot' => 10,
    ]);

    $evaluation->publish(changedBy: $scenario['agent']->id, comment: 'Published.');
    $evaluation->disputeByEmployee(changedBy: $scenario['employeeUser']->id, comment: 'Employee dispute.');

    actingAs($scenario['manager']);

    livewire(ListEvaluations::class)
        ->callAction('resolve_dispute', $evaluation, [
            'resolution_status' => EvaluationStatuses::Rejected->value,
            'comment' => 'Manager confirmed rejection.',
        ]);

    $evaluation->refresh();

    expect($evaluation->status)->toBe(EvaluationStatuses::Rejected)
        ->and($evaluation->resolved_at)->not->toBeNull();
});
