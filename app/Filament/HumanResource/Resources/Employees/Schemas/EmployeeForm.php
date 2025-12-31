<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Enums\EmployeeStatuses;
use App\Enums\Genders;
use App\Models\Citizenship;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->columnSpanFull()
                    ->badge()
                    ->hiddenLabel()
                    ->visibleOn('edit'),
                TextInput::make('first_name')
                    ->autofocus()
                    ->maxLength(255)
                    ->required(),
                TextInput::make('second_first_name')
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('second_last_name')
                    ->maxLength(255),
                Select::make('personal_id_type')
                    ->options(\App\Enums\PersonalIdTypes::class)
                    ->required(),
                TextInput::make('personal_id')
                    ->minLength(10)
                    ->maxLength(11)
                    ->unique(ignoreRecord: true)
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->default(now()->subYears(18)->format('Y-m-d'))
                    ->maxDate(now()->subYears(16)->format('Y-m-d'))
                    ->required(),
                TextInput::make('cellphone')
                    ->unique(ignoreRecord: true)
                    ->minLength(10)
                    ->maxLength(14)
                    ->tel()
                    ->required(),
                Select::make('gender')
                    ->options(Genders::class)
                    ->required(),
                Toggle::make('has_kids')
                    ->required(),
                Select::make('citizenship_id')
                    ->relationship('citizenship', 'name')
                    ->options(ModelListService::get(Citizenship::class))
                    ->required(),
            ]);
    }
}
