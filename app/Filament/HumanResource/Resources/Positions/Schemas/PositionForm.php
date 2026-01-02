<?php

namespace App\Filament\HumanResource\Resources\Positions\Schemas;

use App\Enums\SalaryTypes;
use App\Models\Department;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->options(ModelListService::get(model: Department::class))
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('salary_type')
                    ->enum(SalaryTypes::class)
                    ->options(SalaryTypes::toArray())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('salary')
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
