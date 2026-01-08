<?php

namespace App\Filament\HumanResource\Resources\Terminations\Schemas;

use App\Models\Employee;
use Filament\Schemas\Schema;
use App\Enums\EmployeeStatuses;
use App\Enums\TerminationTypes;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;

class TerminationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(
                        ModelListService::get(
                            model: Employee::query()
                                ->whereIn('status', [
                                    EmployeeStatuses::Hired,
                                    EmployeeStatuses::Terminated
                                ]),
                            value_field: 'full_name'
                            )
                        )
                    ->searchable()
                    ->live()
                    ->required(),
                DateTimePicker::make('date')
                    ->default(now())
                    ->minDate(function (Get $get) {
                        return Employee::query()
                            ->find($get('employee_id'))?->latestHire()->date
                            ?? now()->subMonth();
                    })
                    ->maxDate(now()->addMonths(2)->endOfDay())
                    ->required(),
                Select::make('termination_type')
                    ->options(TerminationTypes::class)
                    ->searchable()
                    ->required(),
                Toggle::make('is_rehireable')
                    ->required()
                    ->default(true),
                Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
