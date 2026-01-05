<?php

namespace App\Filament\HumanResource\Resources\Suspensions\Schemas;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use App\Models\Suspension;
use App\Services\ModelList\Conditions\WhereCondition;
use Filament\Schemas\Schema;
use App\Models\SuspensionType;
use App\Services\ModelList\Conditions\WhereInCondition;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;

class SuspensionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('status')
                    ->columnSpanFull()
                    ->inlineLabel()
                    ->alignLeft(),
                Select::make('employee_id')
                    // ->relationship('employee', 'id')
                    ->autofocus()
                    ->options(ModelListService::get(model: Employee::class, key_field: 'id', value_field: 'full_name', conditions: [
                        new WhereInCondition('status', [
                            EmployeeStatuses::Hired,
                            EmployeeStatuses::Suspended,
                        ])
                    ]))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(onBlur: true),
                Select::make('suspension_type_id')
                    ->label(__('Suspension Type'))
                    ->options(ModelListService::get(SuspensionType::class))
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('starts_at')
                    ->default(now())
                    ->minDate(function (Get $get) {
                        return Employee::query()
                            ->find($get('employee_id'))?->latestHire()->date
                            ?? now();
                    })
                    ->maxDate(now()->addMonths(2)->endOfDay())
                    ->required()
                    ->live(),
                DateTimePicker::make('ends_at')
                    ->default(now()->endOfDay())
                    ->minDate(fn (Get $get) => $get('starts_at') ?? now())
                    ->maxDate(now()->endOfDay()->addYear())
                    ->required(),
                Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
