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
                    ->label(__('ID'))
                    ->columnSpanFull(),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('project.name')
                    ->label(__('Project'))
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label(__('Phone'))
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label(__('Email'))
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (InvoiceAgent $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
