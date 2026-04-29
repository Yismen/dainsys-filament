<?php

use App\Filament\Recruitment\Resources\Applicants\ApplicantResource;
use App\Filament\Recruitment\Resources\Applications\ApplicationResource;
use App\Filament\Recruitment\Resources\ApplicationStageEvents\ApplicationStageEventResource;
use App\Filament\Recruitment\Resources\JobOpenings\JobOpeningResource;
use App\Filament\Recruitment\Resources\RecruitmentStages\RecruitmentStageResource;

describe('Recruitment Resources Modal Configuration', function () {
    describe('ApplicantResource', function () {
        it('is modal-only with no separate pages', function () {
            $pages = ApplicantResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function () {
            $resource = new ApplicantResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function () {
            $resource = new ApplicantResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('RecruitmentStageResource', function () {
        it('is modal-only with no separate pages', function () {
            $pages = RecruitmentStageResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function () {
            $resource = new RecruitmentStageResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function () {
            $resource = new RecruitmentStageResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('JobOpeningResource', function () {
        it('is modal-only with no separate pages', function () {
            $pages = JobOpeningResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function () {
            $resource = new JobOpeningResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function () {
            $resource = new JobOpeningResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('ApplicationResource', function () {
        it('is modal-only with no separate pages', function () {
            $pages = ApplicationResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function () {
            $resource = new ApplicationResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function () {
            $resource = new ApplicationResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });

    describe('ApplicationStageEventResource', function () {
        it('is modal-only with no separate pages', function () {
            $pages = ApplicationStageEventResource::getPages();

            expect($pages)
                ->toHaveKey('index')
                ->not->toHaveKeys(['view', 'create', 'edit']);
        });

        it('has form method defined', function () {
            $resource = new ApplicationStageEventResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('form');
        });

        it('has infolist method defined', function () {
            $resource = new ApplicationStageEventResource;
            $methods = get_class_methods($resource);

            expect($methods)->toContain('infolist');
        });
    });
});
