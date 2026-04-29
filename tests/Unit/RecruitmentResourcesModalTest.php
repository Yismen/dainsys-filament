<?php

use App\Filament\Recruitment\Resources\Applicants\ApplicantResource;
use App\Filament\Recruitment\Resources\Applications\ApplicationResource;
use App\Filament\Recruitment\Resources\ApplicationStageEvents\ApplicationStageEventResource;
use App\Filament\Recruitment\Resources\JobOpenings\JobOpeningResource;
use App\Filament\Recruitment\Resources\RecruitmentStages\RecruitmentStageResource;

describe('Recruitment Resources Modal Configuration', function (): void {
    describe('ApplicantResource', function (): void {
        it('is modal-only with no separate pages', function (): void {
            $pages = ApplicantResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function (): void {
            $resource = new ApplicantResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function (): void {
            $resource = new ApplicantResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('RecruitmentStageResource', function (): void {
        it('is modal-only with no separate pages', function (): void {
            $pages = RecruitmentStageResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function (): void {
            $resource = new RecruitmentStageResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function (): void {
            $resource = new RecruitmentStageResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('JobOpeningResource', function (): void {
        it('is modal-only with no separate pages', function (): void {
            $pages = JobOpeningResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function (): void {
            $resource = new JobOpeningResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function (): void {
            $resource = new JobOpeningResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('ApplicationResource', function (): void {
        it('is modal-only with no separate pages', function (): void {
            $pages = ApplicationResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function (): void {
            $resource = new ApplicationResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function (): void {
            $resource = new ApplicationResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('ApplicationStageEventResource', function (): void {
        it('is modal-only with no separate pages', function (): void {
            $pages = ApplicationStageEventResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function (): void {
            $resource = new ApplicationStageEventResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function (): void {
            $resource = new ApplicationStageEventResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });
});
