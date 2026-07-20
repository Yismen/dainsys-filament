<?php

namespace App\Filament\Invoicing\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id'))
                    ->columnSpanFull(),
                TextEntry::make('name')
                    ->label(__('filament.name')),
                TextEntry::make('client.name')
                    ->label(__('filament.client'))
                    ->placeholder('-'),
                TextEntry::make('invoice_net_days')
                    ->label(__('filament.invoice_net_days'))
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label(__('filament.address'))
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('invoice_notes')
                    ->label(__('filament.invoice_notes'))
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->label(__('filament.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (Project $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
