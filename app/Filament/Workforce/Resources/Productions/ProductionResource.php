<?php

namespace App\Filament\Workforce\Resources\Productions;

use App\Filament\Workforce\Resources\Productions\Pages\CreateProduction;
use App\Filament\Workforce\Resources\Productions\Pages\EditProduction;
use App\Filament\Workforce\Resources\Productions\Pages\ListProductions;
use App\Filament\Workforce\Resources\Productions\Pages\ViewProduction;
use App\Filament\Workforce\Resources\Productions\Schemas\ProductionForm;
use App\Filament\Workforce\Resources\Productions\Schemas\ProductionInfolist;
use App\Filament\Workforce\Resources\Productions\Tables\ProductionsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowTrendingUp;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Imports';

    public static function form(Schema $schema): Schema
    {
        return ProductionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductions::route('/'),
            // 'create' => CreateProduction::route('/create'),
            // 'view' => ViewProduction::route('/{record}'),
            // 'edit' => EditProduction::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
