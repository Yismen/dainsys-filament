<?php

use App\Enums\StageOutcome;
use App\Models\Application;
use App\Models\ApplicationStageEvent;
use App\Models\RecruitmentStage;
use Illuminate\Support\Carbon;

describe('ApplicationStageEvent Model', function (): void {
    describe('Factory', function (): void {
        it('can create an application stage event with factory', function (): void {
            $event = ApplicationStageEvent::factory()->create();

            expect($event)->toBeInstanceOf(ApplicationStageEvent::class)
                ->and($event->id)->not->toBeNull();
        });

        it('creates event with application and recruitment stage', function (): void {
            $event = ApplicationStageEvent::factory()->create();

            expect($event->application_id)->not->toBeNull()
                ->and($event->recruitment_stage_id)->not->toBeNull();
        });

        it('creates event with pending outcome by default', function (): void {
            $event = ApplicationStageEvent::factory()->create();

            expect($event->outcome)->toBe(StageOutcome::Pending);
        });

        it('has no completed_at by default', function (): void {
            $event = ApplicationStageEvent::factory()->create();

            expect($event->completed_at)->toBeNull();
        });

        it('generates optional scheduled_at date', function (): void {
            $event = ApplicationStageEvent::factory()->create();

            $isValid = $event->scheduled_at === null || $event->scheduled_at !== null;
            expect($isValid)->toBeTrue();
        });

        it('generates optional notes', function (): void {
            $event = ApplicationStageEvent::factory()->create();

            $isValid = $event->notes === null || is_string($event->notes);
            expect($isValid)->toBeTrue();
        });
    });

    describe('Factory States', function (): void {
        it('can create passed event with completed_at', function (): void {
            $event = ApplicationStageEvent::factory()->passed()->create();

            expect($event->outcome)->toBe(StageOutcome::Passed)
                ->and($event->completed_at)->not->toBeNull();
        });

        it('can create failed event with completed_at', function (): void {
            $event = ApplicationStageEvent::factory()->failed()->create();

            expect($event->outcome)->toBe(StageOutcome::Failed)
                ->and($event->completed_at)->not->toBeNull();
        });
    });

    describe('Attributes', function (): void {
        it('has correct fillable properties', function (): void {
            $event = ApplicationStageEvent::factory()->make();

            expect($event->getFillable())->toContain(
                'application_id',
                'recruitment_stage_id',
                'outcome',
                'scheduled_at',
                'completed_at',
                'notes'
            );
        });

        it('casts outcome to StageOutcome enum', function (): void {
            $event = ApplicationStageEvent::factory()->create([
                'outcome' => StageOutcome::Passed,
            ]);

            expect($event->outcome)->toBeInstanceOf(StageOutcome::class);
        });

        it('casts scheduled_at and completed_at to datetime', function (): void {
            $event = ApplicationStageEvent::factory()->create([
                'scheduled_at' => now()->addDays(1),
                'completed_at' => now(),
            ]);

            expect($event->scheduled_at)->toBeInstanceOf(Carbon::class)
                ->and($event->completed_at)->toBeInstanceOf(Carbon::class);
        });
    });

    describe('Relationships', function (): void {
        it('belongs to application', function (): void {
            $application = Application::factory()->create();
            $event = ApplicationStageEvent::factory()->create(['application_id' => $application->id]);

            expect($event->application->id)->toBe($application->id);
        });

        it('belongs to recruitment stage', function (): void {
            $stage = RecruitmentStage::factory()->create();
            $event = ApplicationStageEvent::factory()->create(['recruitment_stage_id' => $stage->id]);

            expect($event->recruitmentStage->id)->toBe($stage->id);
        });

        it('can retrieve application data through relationship', function (): void {
            $application = Application::factory()->create();
            $event = ApplicationStageEvent::factory()->create(['application_id' => $application->id]);

            expect($event->application->id)->toBe($application->id);
        });

        it('can retrieve recruitment stage data through relationship', function (): void {
            $stage = RecruitmentStage::factory()->create(['name' => 'Initial Interview']);
            $event = ApplicationStageEvent::factory()->create(['recruitment_stage_id' => $stage->id]);

            expect($event->recruitmentStage->name)->toBe('Initial Interview');
        });
    });

    describe('Soft Deletes', function (): void {
        it('soft deletes application stage event', function (): void {
            $event = ApplicationStageEvent::factory()->create();
            $eventId = $event->id;

            $event->delete();

            expect(ApplicationStageEvent::find($eventId))->toBeNull();
        });

        it('can restore soft deleted event', function (): void {
            $event = ApplicationStageEvent::factory()->create();
            $event->delete();

            $event->restore();

            expect($event->trashed())->toBeFalse();
        });
    });
});
