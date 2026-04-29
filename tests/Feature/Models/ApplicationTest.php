<?php

use App\Enums\ApplicationStatuses;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\ApplicationStageEvent;
use App\Models\JobOpening;
use Illuminate\Support\Carbon;

describe('Application Model', function () {
    describe('Factory', function () {
        it('can create an application with factory', function () {
            $application = Application::factory()->create();

            expect($application)->toBeInstanceOf(Application::class)
                ->and($application->id)->not->toBeNull();
        });

        it('creates application with applicant and job opening', function () {
            $application = Application::factory()->create();

            expect($application->applicant_id)->not->toBeNull()
                ->and($application->job_opening_id)->not->toBeNull();
        });

        it('creates application with applied status by default', function () {
            $application = Application::factory()->create();

            expect($application->status)->toBe(ApplicationStatuses::Applied);
        });

        it('generates optional notes', function () {
            $application = Application::factory()->create();

            $isValid = $application->notes === null || is_string($application->notes);
            expect($isValid)->toBeTrue();
        });

        it('has applied_at date', function () {
            $application = Application::factory()->create();

            expect($application->applied_at)->not->toBeNull();
        });
    });

    describe('Factory States', function () {
        it('can create hired application', function () {
            $application = Application::factory()->hired()->create();

            expect($application->status)->toBe(ApplicationStatuses::Hired);
        });

        it('can create rejected application', function () {
            $application = Application::factory()->rejected()->create();

            expect($application->status)->toBe(ApplicationStatuses::Rejected);
        });

        it('can create in progress application', function () {
            $application = Application::factory()->inProgress()->create();

            expect($application->status)->toBe(ApplicationStatuses::InProgress);
        });
    });

    describe('Attributes', function () {
        it('has correct fillable properties', function () {
            $application = Application::factory()->make();

            expect($application->getFillable())->toContain(
                'applicant_id',
                'job_opening_id',
                'status',
                'notes',
                'applied_at'
            );
        });

        it('casts status to ApplicationStatuses enum', function () {
            $application = Application::factory()->create([
                'status' => ApplicationStatuses::Hired,
            ]);

            expect($application->status)->toBeInstanceOf(ApplicationStatuses::class);
        });

        it('casts applied_at to date format', function () {
            $application = Application::factory()->create();

            expect($application->applied_at)->toBeInstanceOf(Carbon::class);
        });
    });

    describe('Relationships', function () {
        it('belongs to applicant', function () {
            $applicant = Applicant::factory()->create();
            $application = Application::factory()->create(['applicant_id' => $applicant->id]);

            expect($application->applicant->id)->toBe($applicant->id);
        });

        it('belongs to job opening', function () {
            $opening = JobOpening::factory()->create();
            $application = Application::factory()->create(['job_opening_id' => $opening->id]);

            expect($application->jobOpening->id)->toBe($opening->id);
        });

        it('has many application stage events', function () {
            $application = Application::factory()->create();
            $events = ApplicationStageEvent::factory()->count(2)->create([
                'application_id' => $application->id,
            ]);

            expect($application->applicationStageEvents)->toHaveCount(2);
        });

        it('can retrieve applicant name through relationship', function () {
            $applicant = Applicant::factory()->create(['name' => 'Jane Doe']);
            $application = Application::factory()->create(['applicant_id' => $applicant->id]);

            expect($application->applicant->name)->toBe('Jane Doe');
        });

        it('can retrieve job opening title through relationship', function () {
            $opening = JobOpening::factory()->create(['title' => 'Senior Developer']);
            $application = Application::factory()->create(['job_opening_id' => $opening->id]);

            expect($application->jobOpening->title)->toBe('Senior Developer');
        });
    });

    describe('Soft Deletes', function () {
        it('soft deletes application', function () {
            $application = Application::factory()->create();
            $applicationId = $application->id;

            $application->delete();

            expect(Application::find($applicationId))->toBeNull();
        });

        it('can restore soft deleted application', function () {
            $application = Application::factory()->create();
            $application->delete();

            $application->restore();

            expect($application->trashed())->toBeFalse();
        });
    });
});
