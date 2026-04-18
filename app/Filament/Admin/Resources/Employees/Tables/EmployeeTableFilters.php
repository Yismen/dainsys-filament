<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use App\Enums\EmployeeStatuses;
use App\Enums\Genders;
use App\Models\Citizenship;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class EmployeeTableFilters
{
    public static function get(): array
    {
        return [
            SelectFilter::make('citizenship_id')
                ->label(__('filament.citizenship'))
                ->options(ModelListService::make(Citizenship::query()))
                ->searchable(),
            SelectFilter::make('gender')
                ->label(__('filament.gender'))
                ->options(Genders::class)
                ->searchable(),
            SelectFilter::make('status')
                ->label(__('filament.status'))
                ->options(EmployeeStatuses::class)
                ->searchable(),
            TernaryFilter::make('has_kids')
                ->label(__('filament.has_kids')),
            SelectFilter::make('site_id')
                ->options(ModelListService::make(Site::query()))
                ->searchable()
                ->label(__('filament.site')),
            SelectFilter::make('supervisor_id')
                ->options(ModelListService::make(Supervisor::query()))
                ->searchable()
                ->label(__('filament.supervisor')),
            SelectFilter::make('project_id')
                ->options(ModelListService::make(Project::query()))
                ->searchable()
                ->label(__('filament.project')),
            SelectFilter::make('position_id')
                ->options(ModelListService::make(Position::query()))
                ->searchable()
                ->label(__('filament.position')),
        ];
    }
}
