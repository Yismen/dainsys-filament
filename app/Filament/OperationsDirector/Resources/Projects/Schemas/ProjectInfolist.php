<?php

namespace App\Filament\OperationsDirector\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('name')
                    ->label(__('filament.project')),
                TextEntry::make('client.name')
                    ->label(__('filament.client'))
                    ->placeholder('-'),
                TextEntry::make('manager.name')
                    ->label(__('filament.manager'))
                    ->placeholder('Unassigned'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
