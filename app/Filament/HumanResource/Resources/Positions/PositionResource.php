<?php

namespace App\Filament\HumanResource\Resources\Positions;

use BackedEnum;
use App\Models\Position;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Resources\Positions\Pages\EditPosition;
use App\Filament\HumanResource\Resources\Positions\Pages\ViewPosition;
use App\Filament\HumanResource\Resources\Positions\Pages\ListPositions;
use App\Filament\HumanResource\Resources\Positions\Pages\CreatePosition;
use App\Filament\HumanResource\Resources\Positions\Schemas\PositionForm;
use App\Filament\HumanResource\Clusters\HrManagement\HrManagementCluster;
use App\Filament\HumanResource\Resources\Positions\Tables\PositionsTable;
use App\Filament\HumanResource\Resources\Positions\Schemas\PositionInfolist;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = HrManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return PositionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PositionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
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
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'view' => ViewPosition::route('/{record}'),
            'edit' => EditPosition::route('/{record}/edit'),
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
