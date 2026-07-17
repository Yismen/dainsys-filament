<?php

namespace App\Filament\HumanResource\Resources\Afps\Schemas;

use App\Models\Afp;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AfpInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id')),
                TextEntry::make('name'),
                TextEntry::make('person_of_contact')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                RepeatableEntry::make('hiredEmployees')
                    ->label(__('Hired Employees'))
                    ->grid(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('full_name'),
                    ]),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Afp $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
