<?php

namespace App\Filament\HumanResource\Resources\Holidays\Schemas;

use App\Models\Holiday;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HolidayInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->columnSpanFull(),
                TextEntry::make('name'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Holiday $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
