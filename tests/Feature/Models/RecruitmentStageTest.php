<?php

use App\Models\ApplicationStageEvent;
use App\Models\RecruitmentStage;

describe('RecruitmentStage Model', function (): void {
    describe('Factory', function (): void {
        it('can create a recruitment stage with factory', function (): void {
            $stage = RecruitmentStage::factory()->create();

            expect($stage)->toBeInstanceOf(RecruitmentStage::class)
                ->and($stage->id)->not->toBeNull();
        });

        it('generates a name for the stage', function (): void {
            $stage = RecruitmentStage::factory()->create();

            expect($stage->name)->toBeString()->not->toBeEmpty();
        });

        it('generates optional description', function (): void {
            $stage = RecruitmentStage::factory()->create();

            $isValid = $stage->description === null || is_string($stage->description);
            expect($isValid)->toBeTrue();
        });

        it('has an order value', function (): void {
            $stage = RecruitmentStage::factory()->create();

            expect($stage->order)->toBeInt()->toBeBetween(1, 10);
        });
    });

    describe('Attributes', function (): void {
        it('has correct fillable properties', function (): void {
            $stage = RecruitmentStage::factory()->make();

            expect($stage->getFillable())->toContain(
                'name',
                'description',
                'order'
            );
        });

        it('can create stage with specific name and order', function (): void {
            $stage = RecruitmentStage::factory()->create([
                'name' => 'Interview',
                'order' => 2,
            ]);

            expect($stage->name)->toBe('Interview')
                ->and($stage->order)->toBe(2);
        });
    });

    describe('Relationships', function (): void {
        it('has many application stage events', function (): void {
            $stage = RecruitmentStage::factory()->create();
            $events = ApplicationStageEvent::factory()->count(2)->create([
                'recruitment_stage_id' => $stage->id,
            ]);

            expect($stage->applicationStageEvents)->toHaveCount(2);
        });

        it('can retrieve related application stage events', function (): void {
            $stage = RecruitmentStage::factory()->create();
            ApplicationStageEvent::factory()->create(['recruitment_stage_id' => $stage->id]);

            $stage->refresh();

            expect($stage->applicationStageEvents)->toHaveCount(1);
        });
    });

    describe('Soft Deletes', function (): void {
        it('soft deletes recruitment stage', function (): void {
            $stage = RecruitmentStage::factory()->create();
            $stageId = $stage->id;

            $stage->delete();

            expect(RecruitmentStage::find($stageId))->toBeNull();
        });

        it('can restore soft deleted stage', function (): void {
            $stage = RecruitmentStage::factory()->create();
            $stage->delete();

            $stage->restore();

            expect($stage->trashed())->toBeFalse();
        });
    });
});
