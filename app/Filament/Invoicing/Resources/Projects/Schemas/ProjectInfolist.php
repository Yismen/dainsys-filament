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
                    ->label(__('ID'))
                    ->columnSpanFull(),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('client.name')
                    ->label(__('Client'))
                    ->placeholder('-'),
                TextEntry::make('invoice_net_days')
                    ->label(__('Invoice net days'))
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label(__('Address'))
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('invoice_notes')
                    ->label(__('Invoice Notes'))
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (Project $record): bool => $record->trashed()),
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
