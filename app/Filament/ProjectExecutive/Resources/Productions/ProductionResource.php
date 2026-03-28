<?php

namespace App\Filament\ProjectExecutive\Resources\Productions;

use App\Filament\ProjectExecutive\Resources\Productions\Pages\ListProductions;
use App\Filament\ProjectExecutive\Resources\Productions\Schemas\ProductionInfolist;
use App\Filament\ProjectExecutive\Resources\Productions\Tables\ProductionsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

    public static function getEloquentQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee.project', function (Builder $query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductions::route('/'),
        ];
    }
}
