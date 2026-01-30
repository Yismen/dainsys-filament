<?php

namespace App\Filament\HumanResource\Resources\Positions;

use App\Filament\HumanResource\Resources\Positions\Pages\CreatePosition;
use App\Filament\HumanResource\Resources\Positions\Pages\EditPosition;
use App\Filament\HumanResource\Resources\Positions\Pages\ListPositions;
use App\Filament\HumanResource\Resources\Positions\Pages\ViewPosition;
use App\Filament\HumanResource\Resources\Positions\Schemas\PositionForm;
use App\Filament\HumanResource\Resources\Positions\Schemas\PositionInfolist;
use App\Filament\HumanResource\Resources\Positions\Tables\PositionsTable;
use App\Models\Position;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::HR_MANAGEMENT;

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
