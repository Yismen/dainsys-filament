<?php

namespace App\Filament\Invoicing\Resources\Items\Schemas;

use App\Models\Item;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemInfolist
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
                TextEntry::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->label(__('filament.price'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('filament.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                Section::make('Additional information')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('image')
                            ->label(__('filament.image_url'))
                            ->url('')
                            ->placeholder('https://example.com/image.jpg'),
                        TextEntry::make('category')
                            ->label(__('filament.category'))
                            ->placeholder(__('filament.example_categories')),
                        TextEntry::make('brand')
                            ->label(__('filament.brand'))
                            ->placeholder(__('filament.example_clients')),
                        TextEntry::make('sku')
                            ->label(__('filament.sku'))
                            ->placeholder(__('filament.stock_keeping_unit')),
                        TextEntry::make('barcode')
                            ->label(__('filament.barcode'))
                            ->placeholder(__('filament.example_identifiers')),
                    ]),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (Item $record): bool => $record->trashed()),
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
