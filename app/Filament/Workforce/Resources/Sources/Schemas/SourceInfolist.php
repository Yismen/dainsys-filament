<?php

namespace App\Filament\Workforce\Resources\Sources\Schemas;

use App\Models\Source;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SourceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Source $record): bool => $record->trashed()),
            ]);
    }
}
