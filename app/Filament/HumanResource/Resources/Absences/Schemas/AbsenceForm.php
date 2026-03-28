<?php

namespace App\Filament\HumanResource\Resources\Absences\Schemas;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Models\Absence;
use App\Models\Employee;
use App\Rules\UniqueCombination;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;

class AbsenceForm
{
    public static function schema(): array
    {
        return [
            Select::make('employee_id')
                ->label('Employee')
                ->options(ModelListService::make(model: Employee::query()->active(), value_field: 'full_name'))
                ->searchable()
                ->required()
                ->autofocus()
                ->rules([
                    fn (?Absence $record, Get $get): array => [
                        new UniqueCombination(
                            model: Absence::class,
                            fields: [
                                'employee_id' => $get('employee_id'),
                                'date' => $get('date')
                                ],
                            exceptId: $record?->id,
                        ),
                    ]
                ]),
            DatePicker::make('date')
                ->required()
                ->default(now()->subDay())
                ->minDate(now()->subYear())
                ->maxDate(now()),
            Select::make('status')
                ->label('Status')
                ->options(AbsenceStatuses::toArray())
                ->default(AbsenceStatuses::Created)
                ->required(),
            Select::make('type')
                ->label('Type')
                ->required()
                ->options(AbsenceTypes::toArray()),
            Textarea::make('comment')
                ->label('Comment')
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
