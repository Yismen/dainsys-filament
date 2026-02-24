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
            SelectFilter::make('citizenship')
                ->relationship('citizenship', 'name')
                ->options(ModelListService::make(Citizenship::query()))
                ->searchable(),
            SelectFilter::make('gender')
                ->options(Genders::class)
                ->searchable(),
            SelectFilter::make('status')
                ->options(EmployeeStatuses::class)
                ->searchable(),
            TernaryFilter::make('has_kids'),
            SelectFilter::make('site')
                ->relationship('site', 'id')
                ->options(ModelListService::make(Site::query()))
                ->searchable()
                ->label('Site'),
            SelectFilter::make('supervisor')
                ->relationship('supervisor', 'id')
                ->options(ModelListService::make(Supervisor::query()))
                ->searchable()
                ->label('Supervisor'),
            SelectFilter::make('project')
                ->relationship('project', 'id')
                ->options(ModelListService::make(Project::query()))
                ->searchable()
                ->label('Project'),
            SelectFilter::make('position')
                ->relationship('position', 'id')
                ->options(ModelListService::make(Position::query()))
                ->searchable()
                ->label('Position'),
        ];
    }
}
