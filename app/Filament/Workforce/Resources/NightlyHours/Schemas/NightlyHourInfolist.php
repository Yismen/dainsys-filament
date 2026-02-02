<?php

namespace App\Filament\Workforce\Resources\NightlyHours\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NightlyHourInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee.name')
                    ->label('Employee'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('total_hours')
                    ->label('Total Hours')
                    ->numeric(decimalPlaces: 2),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}
