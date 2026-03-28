<?php

namespace App\Filament\OperationsDirector\Resources\Productions;

use App\Filament\OperationsDirector\Resources\Productions\Pages\ListProductions;
use App\Filament\OperationsDirector\Resources\Productions\Schemas\ProductionInfolist;
use App\Filament\OperationsDirector\Resources\Productions\Tables\ProductionsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?int $navigationSort = 3;

    public static function infolist(Schema $schema): Schema
    {
        return ProductionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductions::route('/'),
        ];
    }
}
