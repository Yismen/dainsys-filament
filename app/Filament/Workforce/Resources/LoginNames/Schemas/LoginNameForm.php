<?php

namespace App\Filament\Workforce\Resources\LoginNames\Schemas;

use App\Filament\Schemas\Workforce\EmployeeSchema;
use App\Filament\Workforce\Resources\Employees\Schemas\EmployeeForm;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class LoginNameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('login_name')
                    ->unique(ignoreRecord: true)
                    ->autofocus()
                    ->required(),
                Select::make('employee_id')
                    ->options(ModelListService::get(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required()
                    ->relationship('employee', 'full_name')
                    ->createOptionForm([
                        Grid::make(2)
                            ->schema(
                                EmployeeSchema::make()
                            )
                    ])
                    ->preload(10)
                    ->createOptionModalHeading('Create Employee'),
            ]);
    }
}
