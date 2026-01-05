<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Schemas;

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
                    ->relationship('employee', 'id')
                    ->required(),
                Select::make('ars_id')
                    ->relationship('ars', 'name')
                    ->required(),
                Select::make('afp_id')
                    ->relationship('afp', 'name')
                    ->required(),
                TextInput::make('number')
                    ->required(),
            ]);
    }
}
