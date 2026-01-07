<?php

namespace App\Filament\Workforce\Resources\LoginNames\Schemas;

use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->required(),
            ]);
    }
}
