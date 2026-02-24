<?php

namespace App\Filament\HumanResource\Resources\Universals\Schemas;

use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UniversalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(ModelListService::get(
                        model: Employee::query()->active(),
                        value_field: 'full_name'
                        ))
                    ->searchable()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->autofocus(),
                DatePicker::make('date_since')
                    ->required(),
            ]);
    }
}
