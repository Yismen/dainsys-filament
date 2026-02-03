<?php

namespace App\Filament\Workforce\Resources\NightlyHours\Schemas;

use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NightlyHourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(ModelListService::make(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                DatePicker::make('date')
                    ->default(now())
                    ->minDate(now()->startOfDay()->subDays(30))
                    ->maxDate(now())
                    ->required(),
                TextInput::make('total_hours')
                    ->label('Total Hours')
                    ->numeric()
                    ->required(),
            ]);
    }
}
