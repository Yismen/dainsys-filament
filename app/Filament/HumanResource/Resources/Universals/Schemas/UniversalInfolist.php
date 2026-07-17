<?php

namespace App\Filament\HumanResource\Resources\Universals\Schemas;

use App\Models\Universal;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UniversalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id')),
                TextEntry::make('employee.full_name')
                    ->label(__('filament.employee')),
                TextEntry::make('date_since')
                    ->date(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Universal $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
