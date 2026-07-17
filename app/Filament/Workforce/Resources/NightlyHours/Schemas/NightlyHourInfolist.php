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
                TextEntry::make('employee.full_name')
                    ->label(__('filament.employee')),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('total_hours')
                    ->label(__('filament.total_hours'))
                    ->numeric(decimalPlaces: 2),
                TextEntry::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime(),
            ]);
    }
}
