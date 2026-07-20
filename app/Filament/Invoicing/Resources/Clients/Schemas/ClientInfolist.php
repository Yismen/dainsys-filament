<?php

namespace App\Filament\Invoicing\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClientInfolist
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
                TextEntry::make('person_of_contact')
                    ->label(__('filament.person_of_contact'))
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label(__('filament.phone'))
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label(__('filament.email'))
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label(__('filament.address'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('website')
                    ->label(__('filament.website'))
                    ->placeholder('-'),
                TextEntry::make('invoice_template')
                    ->label(__('filament.invoice_template'))
                    ->placeholder('-'),
                TextEntry::make('date_field_name')
                    ->label(__('filament.date_field_name'))
                    ->placeholder('-'),
                TextEntry::make('project_field_name')
                    ->label(__('filament.project_field_name'))
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('filament.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (Client $record): bool => $record->trashed()),
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
