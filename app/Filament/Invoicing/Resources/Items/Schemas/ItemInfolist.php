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
                    ->label(__('ID'))
                    ->columnSpanFull(),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('campaign.name')
                    ->label(__('Campaign'))
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->label(__('Price'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                Section::make('Additional information')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('image')
                            ->label(__('Image URL'))
                            ->url('')
                            ->placeholder('https://example.com/image.jpg'),
                        TextEntry::make('category')
                            ->label(__('Category'))
                            ->placeholder(__('e.g., Electronics, Clothing, etc.')),
                        TextEntry::make('brand')
                            ->label(__('Brand'))
                            ->placeholder(__('e.g., Apple, Nike, etc.')),
                        TextEntry::make('sku')
                            ->label(__('SKU'))
                            ->placeholder(__('Stock Keeping Unit')),
                        TextEntry::make('barcode')
                            ->label(__('Barcode'))
                            ->placeholder(__('e.g., UPC, EAN, etc.')),
                    ]),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (Item $record): bool => $record->trashed()),
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
