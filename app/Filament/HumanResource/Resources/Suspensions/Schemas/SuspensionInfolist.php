<?php

namespace App\Filament\HumanResource\Resources\Suspensions\Schemas;

use App\Models\Suspension;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SuspensionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('suspensionType.name')
                    ->label('Suspension type'),
                TextEntry::make('starts_at')
                    ->dateTime(),
                TextEntry::make('ends_at')
                    ->dateTime(),
                TextEntry::make('status'),
                TextEntry::make('comment')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Suspension $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
