<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Models\Site;
use App\Enums\Genders;
use App\Models\Project;
use App\Models\Position;
use App\Models\Supervisor;
use App\Models\Citizenship;
use App\Enums\EmployeeStatuses;
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
                ->searchable()
                ->preload(),
            SelectFilter::make('gender')
                ->options(Genders::class)
                ->searchable(),
            SelectFilter::make('status')
                ->options(EmployeeStatuses::class)
                ->searchable(),
            TernaryFilter::make('has_kids'),
            SelectFilter::make('site')
                ->relationship('hires', 'site_id')
                ->options(ModelListService::make(Site::query()))
                ->searchable()
                ->preload()
                ->label('Site (Latest Hire)'),
            SelectFilter::make('supervisor')
                ->relationship('hires', 'supervisor_id')
                ->options(ModelListService::make(Supervisor::query()))
                ->searchable()
                ->preload()
                ->label('Supervisor (Latest Hire)'),
            SelectFilter::make('project')
                ->relationship('hires', 'project_id')
                ->options(ModelListService::make(Project::query()))
                ->searchable()
                ->preload()
                ->label('Project (Latest Hire)'),
            SelectFilter::make('position')
                ->relationship('hires', 'position_id')
                ->options(ModelListService::make(Position::query()))
                ->searchable()
                ->preload()
                ->label('Position (Latest Hire)'),
        ];
    }
}
