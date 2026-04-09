<?php

namespace App\Filament\Invoicing\Resources\Items\Schemas;

use App\Models\Campaign;
use App\Services\ModelListService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                Select::make('campaign_id')
                    ->label(__('Campaign'))
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('price')
                    ->label(__('Price'))
                    ->inputMode('decimal')
                    ->step('any')
                    ->rule('decimal:0,13')
                    ->required(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
                Section::make('Additional information')
                    ->columnSpanFull()
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        FileUpload::make('image')
                            ->label(__('Image URL'))
                            ->image()
                            ->placeholder('https://example.com/image.jpg'),
                        TextInput::make('category')
                            ->label(__('Category'))
                            ->placeholder(__('e.g., Electronics, Clothing, etc.')),
                        TextInput::make('brand')
                            ->label(__('Brand'))
                            ->placeholder(__('e.g., Apple, Nike, etc.')),
                        TextInput::make('sku')
                            ->label(__('SKU'))
                            ->placeholder(__('Stock Keeping Unit')),
                        TextInput::make('barcode')
                            ->label(__('Barcode'))
                            ->placeholder(__('e.g., UPC, EAN, etc.')),
                    ]),
            ]);
    }
}
