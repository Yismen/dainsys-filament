<?php

use App\Enums\JobOpeningStatuses;
use App\Models\Application;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\Position;
use App\Models\Site;
use Illuminate\Support\Carbon;

describe('JobOpening Model', function (): void {
    describe('Factory', function (): void {
        it('can create a job opening with factory', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening)->toBeInstanceOf(JobOpening::class)
                ->and($opening->id)->not->toBeNull();
        });

        it('generates a job title', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening->title)->toBeString()->not->toBeEmpty();
        });

        it('generates optional description', function (): void {
            $opening = JobOpening::factory()->create();

            $isValid = $opening->description === null || is_string($opening->description);
            expect($isValid)->toBeTrue();
        });

        it('creates opening with open status by default', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening->status)->toBe(JobOpeningStatuses::Open);
        });

        it('has openings count', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening->openings_count)->toBeInt()->toBeBetween(1, 5);
        });

        it('has opened_at date', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening->opened_at)->not->toBeNull();
        });

        it('has closed_at null by default', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening->closed_at)->toBeNull();
        });
    });

    describe('Factory States', function (): void {
        it('can create closed job opening', function (): void {
            $opening = JobOpening::factory()->closed()->create();

            expect($opening->status)->toBe(JobOpeningStatuses::Closed)
                ->and($opening->closed_at)->not->toBeNull();
        });

        it('can create on hold job opening', function (): void {
            $opening = JobOpening::factory()->onHold()->create();

            expect($opening->status)->toBe(JobOpeningStatuses::OnHold);
        });
    });

    describe('Attributes', function (): void {
        it('has correct fillable properties', function (): void {
            $opening = JobOpening::factory()->make();

            expect($opening->getFillable())->toContain(
                'title',
                'description',
                'status',
                'position_id',
                'department_id',
                'site_id',
                'openings_count',
                'opened_at',
                'closed_at'
            );
        });

        it('casts status to JobOpeningStatuses enum', function (): void {
            $opening = JobOpening::factory()->create([
                'status' => JobOpeningStatuses::Open,
            ]);

            expect($opening->status)->toBeInstanceOf(JobOpeningStatuses::class);
        });

        it('casts dates to date format', function (): void {
            $opening = JobOpening::factory()->create();

            expect($opening->opened_at)->toBeInstanceOf(Carbon::class);

            if ($opening->closed_at !== null) {
                expect($opening->closed_at)->toBeInstanceOf(Carbon::class);
            }
        });
    });

    describe('Relationships', function (): void {
        it('belongs to position', function (): void {
            $position = Position::factory()->create();
            $opening = JobOpening::factory()->create(['position_id' => $position->id]);

            expect($opening->position->id)->toBe($position->id);
        });

        it('belongs to department', function (): void {
            $department = Department::factory()->create();
            $opening = JobOpening::factory()->create(['department_id' => $department->id]);

            expect($opening->department->id)->toBe($department->id);
        });

        it('belongs to site', function (): void {
            $site = Site::factory()->create();
            $opening = JobOpening::factory()->create(['site_id' => $site->id]);

            expect($opening->site->id)->toBe($site->id);
        });

        it('has many applications', function (): void {
            $opening = JobOpening::factory()->create();
            $applications = Application::factory()->count(3)->create([
                'job_opening_id' => $opening->id,
            ]);

            expect($opening->applications)->toHaveCount(3);
        });
    });

    describe('Soft Deletes', function (): void {
        it('soft deletes job opening', function (): void {
            $opening = JobOpening::factory()->create();
            $openingId = $opening->id;

            $opening->delete();

            expect(JobOpening::find($openingId))->toBeNull();
        });

        it('can restore soft deleted opening', function (): void {
            $opening = JobOpening::factory()->create();
            $opening->delete();

            $opening->restore();

            expect($opening->trashed())->toBeFalse();
        });
    });
});
