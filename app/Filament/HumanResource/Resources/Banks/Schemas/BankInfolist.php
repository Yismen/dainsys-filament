<?php

namespace App\Filament\HumanResource\Resources\Banks\Schemas;

use App\Models\Bank;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BankInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->columnSpanFull(),
                TextEntry::make('name'),
                TextEntry::make('person_of_contact')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Bank $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
