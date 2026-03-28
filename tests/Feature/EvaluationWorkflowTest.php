<?php

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationQuestionScore;
use App\Models\QAForm;
use App\Models\QAQuestion;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\User;
use App\Notifications\EvaluationCreatedNotification;
use App\Notifications\EvaluationDisputedNotification;
use App\Notifications\EvaluationPublishedNotification;
use App\Notifications\EvaluationResolvedNotification;
use Illuminate\Support\Facades\Notification;

function createEvaluationScenario(): array
{
    Notification::fake();

    Role::firstOrCreate(['name' => QARoles::Manager->value], ['guard_name' => 'web']);
    Role::firstOrCreate(['name' => QARoles::Agent->value], ['guard_name' => 'web']);

    $qaManager = User::factory()->create();
    $qaManager->assignRole(QARoles::Manager->value);

    $evaluator = User::factory()->create();
    $evaluator->assignRole(QARoles::Agent->value);

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
        'created_by' => $qaManager->id,
    ]);

    return compact('qaManager', 'evaluator', 'supervisorUser', 'supervisor', 'employee', 'employeeUser', 'form');
}

it('creates evaluations in draft and notifies all required recipients', function (): void {
    $scenario = createEvaluationScenario();

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['evaluator']->id,
        'qa_form_id' => $scenario['form']->id,
        'threshold_percentage' => $scenario['form']->passing_threshold_percentage,
        'comments' => 'Initial draft evaluation',
    ]);

    expect($evaluation->status)->toBe(EvaluationStatuses::Draft);

    Notification::assertSentTo($scenario['employeeUser'], EvaluationCreatedNotification::class);
    Notification::assertSentTo($scenario['supervisorUser'], EvaluationCreatedNotification::class);
    Notification::assertSentTo($scenario['evaluator'], EvaluationCreatedNotification::class);
    Notification::assertSentTo($scenario['qaManager'], EvaluationCreatedNotification::class);
});

it('publishes evaluation, recalculates totals, and notifies employee and supervisor', function (): void {
    $scenario = createEvaluationScenario();

    $questionOne = QAQuestion::factory()->create([
        'qa_form_id' => $scenario['form']->id,
        'author_id' => $scenario['qaManager']->id,
        'max_points' => 10,
        'display_order' => 1,
    ]);

    $questionTwo = QAQuestion::factory()->create([
        'qa_form_id' => $scenario['form']->id,
        'author_id' => $scenario['qaManager']->id,
        'max_points' => 10,
        'display_order' => 2,
    ]);

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['evaluator']->id,
        'qa_form_id' => $scenario['form']->id,
        'threshold_percentage' => 80,
        'comments' => 'Pending publish',
    ]);

    EvaluationQuestionScore::query()->create([
        'evaluation_id' => $evaluation->id,
        'qa_question_id' => $questionOne->id,
        'points_awarded' => 8,
        'max_points_snapshot' => 10,
    ]);

    EvaluationQuestionScore::query()->create([
        'evaluation_id' => $evaluation->id,
        'qa_question_id' => $questionTwo->id,
        'points_awarded' => 9,
        'max_points_snapshot' => 10,
    ]);

    $evaluation->publish(changedBy: $scenario['evaluator']->id, comment: 'Publishing for review');
    $evaluation->refresh();

    expect($evaluation->status)->toBe(EvaluationStatuses::Published)
        ->and($evaluation->points_possible)->toBe(20)
        ->and($evaluation->points_achieved)->toBe(17)
        ->and((float) $evaluation->success_percentage)->toBe(85.0)
        ->and($evaluation->published_at)->not->toBeNull();

    Notification::assertSentTo($scenario['employeeUser'], EvaluationPublishedNotification::class);
    Notification::assertSentTo($scenario['supervisorUser'], EvaluationPublishedNotification::class);
});

it('disputes and resolves an evaluation with manager notifications', function (): void {
    $scenario = createEvaluationScenario();

    $question = QAQuestion::factory()->create([
        'qa_form_id' => $scenario['form']->id,
        'author_id' => $scenario['qaManager']->id,
        'max_points' => 10,
        'display_order' => 1,
    ]);

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['evaluator']->id,
        'qa_form_id' => $scenario['form']->id,
        'threshold_percentage' => 80,
    ]);

    EvaluationQuestionScore::query()->create([
        'evaluation_id' => $evaluation->id,
        'qa_question_id' => $question->id,
        'points_awarded' => 6,
        'max_points_snapshot' => 10,
    ]);

    $evaluation->publish(changedBy: $scenario['evaluator']->id);
    $evaluation->disputeByEmployee(changedBy: $scenario['employeeUser']->id, comment: 'I disagree with score details.');

    $evaluation->refresh();
    expect($evaluation->status)->toBe(EvaluationStatuses::Disputed);
    Notification::assertSentTo($scenario['qaManager'], EvaluationDisputedNotification::class);

    $evaluation->resolveDispute(
        resolutionStatus: EvaluationStatuses::Rejected,
        changedBy: $scenario['qaManager']->id,
        comment: 'After review, evaluation remains rejected.'
    );

    $evaluation->refresh();
    expect($evaluation->status)->toBe(EvaluationStatuses::Rejected)
        ->and($evaluation->resolved_at)->not->toBeNull();

    Notification::assertSentTo($scenario['employeeUser'], EvaluationResolvedNotification::class);
    Notification::assertSentTo($scenario['supervisorUser'], EvaluationResolvedNotification::class);
    Notification::assertSentTo($scenario['evaluator'], EvaluationResolvedNotification::class);
});

it('prevents publishing without scored questions', function (): void {
    $scenario = createEvaluationScenario();

    $evaluation = Evaluation::query()->create([
        'evaluation_date' => now()->toDateString(),
        'employee_id' => $scenario['employee']->id,
        'evaluator_id' => $scenario['evaluator']->id,
        'qa_form_id' => $scenario['form']->id,
        'threshold_percentage' => 80,
    ]);

    expect(fn () => $evaluation->publish(changedBy: $scenario['evaluator']->id))
        ->toThrow(DomainException::class);
});
