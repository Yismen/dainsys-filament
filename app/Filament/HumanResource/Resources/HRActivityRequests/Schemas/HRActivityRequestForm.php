<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Schemas;

use App\Enums\HRActivityTypes;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HRActivityRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'id')
                    ->required(),
                Select::make('supervisor_id')
                    ->relationship('supervisor', 'name')
                    ->required(),
                Select::make('activity_type')
                    ->options(HRActivityTypes::class)
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('requested_at')
                    ->required(),
            ]);
    }
}
