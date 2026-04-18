<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Schemas;

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SocialSecurityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::get(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                Select::make('ars_id')
                    ->label(__('filament.ars'))
                    ->options(ModelListService::get(model: Ars::query()))
                    ->searchable()
                    ->required(),
                Select::make('afp_id')
                    ->label(__('filament.afp'))
                    ->options(ModelListService::get(model: Afp::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('number')
                    ->label(__('filament.tss_number'))
                    ->nullable(),
            ]);
    }
}
