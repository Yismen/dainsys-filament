<?php

namespace App\Filament\HumanResource\Resources\Absences\Schemas;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class AbsenceForm
{
    public static function schema(): array
    {
        return [
            Select::make('employee_id')
                ->label('Employee')
                ->options(ModelListService::make(model: Employee::query()->active(), value_field: 'full_name'))
                ->searchable()
                ->required(),
            DatePicker::make('date')
                ->required()
                ->minDate(now()->subYear())
                ->maxDate(now()),
            Select::make('status')
                ->label('Status')
                ->options(AbsenceStatuses::toArray())
                ->default(AbsenceStatuses::Created)
                ->required(),
            Select::make('type')
                ->label('Type')
                ->options(AbsenceTypes::toArray())
                ->placeholder('Pending'),
            Textarea::make('comment')
                ->label('Comment')
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
