<?php

namespace App\Filament\HumanResource\Resources\Terminations\Schemas;

use App\Models\Termination;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TerminationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->columnSpanFull(),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('date')
                    ->dateTime(),
                TextEntry::make('termination_type'),
                IconEntry::make('is_rehireable')
                    ->boolean(),
                TextEntry::make('comment')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Termination $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
