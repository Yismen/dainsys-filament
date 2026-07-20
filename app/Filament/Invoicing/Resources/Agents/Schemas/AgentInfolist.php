<?php

namespace App\Filament\Invoicing\Resources\Agents\Schemas;

use App\Models\InvoiceAgent;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AgentInfolist
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
                TextEntry::make('project.name')
                    ->label(__('filament.project'))
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label(__('filament.phone'))
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label(__('filament.email'))
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (InvoiceAgent $record): bool => $record->trashed()),
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
