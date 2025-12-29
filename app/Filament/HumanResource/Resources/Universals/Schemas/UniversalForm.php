<?php

namespace App\Filament\HumanResource\Resources\Universals\Schemas;

use App\Models\Employee;
use Filament\Schemas\Schema;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class UniversalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'id')
                    ->options(ModelListService::get(model: Employee::class, value_field: 'full_name'))
                    ->searchable()
                    ->preload()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->autofocus(),
                DatePicker::make('date_since')
                    ->required(),
            ]);
    }
}
