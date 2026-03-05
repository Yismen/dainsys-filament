<?php

namespace App\Schemas\Filament\HumanResource;

use App\Enums\EmployeeStatuses;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class HireEmployeeSchema
{
    public static function make(): array
    {
        return [
            Select::make('site_id')
                ->searchable()
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->options(ModelListService::make(Site::query())),
            Select::make('project_id')
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->searchable()
                ->options(ModelListService::make(Project::query())),
            Select::make('position_id')
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->searchable()
                ->options(ModelListService::make(Position::query())),
            Select::make('supervisor_id')
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->options(ModelListService::make(Supervisor::query()))
                ->searchable(),
            DateTimePicker::make('hired_at')
                ->nullable()
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created),
            TextInput::make('internal_id')
                ->nullable()
                ->unique(ignoreRecord: true)
                ->minLength(4)
                ->maxLength(20),
        ];
    }
}
