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
                ->options(ModelListService::make(Site::query()))
                ->required(fn ($record) => $record->status !== EmployeeStatuses::Created),
            Select::make('project_id')
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->searchable()
                ->options(ModelListService::make(Project::query()))
                ->required(fn ($record) => $record->status !== EmployeeStatuses::Created),
            Select::make('position_id')
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->searchable()
                ->options(ModelListService::make(Position::query()))
                ->required(fn ($record) => $record->status !== EmployeeStatuses::Created),
            Select::make('supervisor_id')
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->options(ModelListService::make(Supervisor::query()))
                ->searchable()
                ->required(fn ($record) => $record->status !== EmployeeStatuses::Created),
            DateTimePicker::make('hired_at')
                ->nullable()
                ->default(now())
                ->maxDate(now()->addDays(5))
                ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                ->required(fn ($record) => $record->status !== EmployeeStatuses::Created),
            TextInput::make('internal_id')
                ->nullable()
                ->unique(ignoreRecord: true)
                ->minLength(4)
                ->maxLength(20)
                ->required(fn ($record) => $record->status !== EmployeeStatuses::Created),
        ];
    }
}
