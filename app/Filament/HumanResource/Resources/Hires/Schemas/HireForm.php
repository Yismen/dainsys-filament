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
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(ModelListService::get(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                DateTimePicker::make('date')
                    ->required(),
                Select::make('site_id')
                    ->options(ModelListService::get(Site::query()))
                    ->searchable()
                    ->required(),
                Select::make('project_id')
                    ->options(ModelListService::get(Project::query()))
                    ->searchable()
                    ->required(),
                Select::make('position_id')
                    ->options(ModelListService::get(Position::query()))
                    ->searchable()
                    ->required(),
                Select::make('supervisor_id')
                    ->options(ModelListService::get(Supervisor::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('punch'),
            ]);
    }
}
