<?php

namespace App\Filament\Workforce\Resources\Productions\Schemas;

use App\Models\Campaign;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->autofocus()
                    ->default(now())
                    ->required(),
                Select::make('employee_id')
                    ->options(ModelListService::get(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                Select::make('campaign_id')
                    ->options(ModelListService::get(Campaign::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('conversions')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_time')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('production_time')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('talk_time')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
