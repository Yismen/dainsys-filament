<?php

namespace App\Filament\HumanResource\Resources\Hires\Schemas;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class HireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::get(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                DateTimePicker::make('date')
                    ->label(__('filament.date'))
                    ->required(),
                Select::make('site_id')
                    ->label(__('filament.site'))
                    ->options(ModelListService::get(Site::query()))
                    ->searchable()
                    ->required(),
                Select::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::get(Project::query()))
                    ->searchable()
                    ->required(),
                Select::make('position_id')
                    ->label(__('filament.position'))
                    ->options(ModelListService::get(Position::query()))
                    ->searchable()
                    ->required(),
                Select::make('supervisor_id')
                    ->label(__('filament.supervisor'))
                    ->options(ModelListService::get(Supervisor::query()))
                    ->searchable()
                    ->required(),
            ]);
    }
}
