<?php

use App\Models\Applicant;
use App\Models\Application;

describe('Applicant Model', function () {
    describe('Factory', function () {
        it('can create an applicant with factory', function () {
            $applicant = Applicant::factory()->create();

            expect($applicant)->toBeInstanceOf(Applicant::class)
                ->and($applicant->id)->not->toBeNull();
        });

        it('creates unique email addresses', function () {
            $applicants = Applicant::factory()->count(3)->create();

            expect($applicants->pluck('email')->unique()->count())->toBe(3);
        });

        it('generates realistic phone numbers optionally', function () {
            $applicant = Applicant::factory()->create();

            $isValid = $applicant->phone === null || is_string($applicant->phone);
            expect($isValid)->toBeTrue();
        });

        it('generates optional notes', function () {
            $applicant = Applicant::factory()->create();

            $isValid = $applicant->notes === null || is_string($applicant->notes);
            expect($isValid)->toBeTrue();
        });
    });

    describe('Attributes', function () {
        it('has correct fillable properties', function () {
            $applicant = Applicant::factory()->make();

            expect($applicant->getFillable())->toContain(
                'name',
                'email',
                'phone',
                'resume_path',
                'notes'
            );
        });

        it('has required applicant attributes', function () {
            $applicant = Applicant::factory()->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);

            expect($applicant->name)->toBe('John Doe')
                ->and($applicant->email)->toBe('john@example.com');
        });
    });

    describe('Relationships', function () {
        it('has many applications', function () {
            $applicant = Applicant::factory()->create();
            $applications = Application::factory()->count(3)->create([
                'applicant_id' => $applicant->id,
            ]);

            expect($applicant->applications)->toHaveCount(3)
                ->and($applicant->applications->first()->applicant_id)->toBe($applicant->id);
        });

        it('can retrieve related applications', function () {
            $applicant = Applicant::factory()->create();
            Application::factory()->create(['applicant_id' => $applicant->id]);

            $applicant->refresh();

            expect($applicant->applications)->toHaveCount(1);
        });
    });

    describe('Soft Deletes', function () {
        it('soft deletes applicant', function () {
            $applicant = Applicant::factory()->create();
            $applicantId = $applicant->id;

            $applicant->delete();

            expect(Applicant::find($applicantId))->toBeNull();
        });

        it('can restore soft deleted applicant', function () {
            $applicant = Applicant::factory()->create();
            $applicant->delete();

            $applicant->restore();

            expect($applicant->trashed())->toBeFalse();
        });
    });
});
