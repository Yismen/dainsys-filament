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
                    ->label(__('ID'))
                    ->columnSpanFull(),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('person_of_contact')
                    ->label(__('Person of contact'))
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->label(__('Phone'))
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label(__('Email'))
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label(__('Address'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('website')
                    ->label(__('Website'))
                    ->placeholder('-'),
                TextEntry::make('invoice_template')
                    ->label(__('Invoice template'))
                    ->placeholder('-'),
                TextEntry::make('date_field_name')
                    ->label(__('Date field name'))
                    ->placeholder('-'),
                TextEntry::make('project_field_name')
                    ->label(__('Project field name'))
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (Client $record): bool => $record->trashed()),
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
