<?php

namespace App\Schemas\Filament\HumanResource;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

class BankAccountSchema
{
    public static function make(): array
    {
        return [
            Select::make('employee_id')
                ->options(
                    ModelListService::make(
                        model: Employee::query(),
                        value_field: 'full_name',
                    )
                )
                ->unique(ignoreRecord: true, table: (new BankAccount)->getTable())
                ->searchable()
                ->required(),
            Select::make('bank_id')
                ->relationship('bank', 'name')
                ->options(
                    ModelListService::make(Bank::query())
                )
                ->searchable()
                ->createOptionForm([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true, table: (new Bank)->getTable())
                                ->autofocus(),
                            TextInput::make('person_of_contact'),
                            TextInput::make('phone')
                                ->tel(),
                            TextInput::make('email')
                                ->email(),
                            Textarea::make('description')
                                ->columnSpanFull(),
                        ]),
                ])
                ->required(),
            TextInput::make('account')
                ->required()
                ->minLength(5)
                ->maxLength(50)
                ->trim()
                ->unique(ignoreRecord: true, table: (new BankAccount)->getTable()),
        ];
    }
}
