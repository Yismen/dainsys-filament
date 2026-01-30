<?php

namespace App\Filament\HumanResource\Resources\SuspensionTypes;

use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\CreateSuspensionType;
use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\EditSuspensionType;
use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\ListSuspensionTypes;
use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\ViewSuspensionType;
use App\Filament\HumanResource\Resources\SuspensionTypes\Schemas\SuspensionTypeForm;
use App\Filament\HumanResource\Resources\SuspensionTypes\Schemas\SuspensionTypeInfolist;
use App\Filament\HumanResource\Resources\SuspensionTypes\Tables\SuspensionTypesTable;
use App\Models\SuspensionType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuspensionTypeResource extends Resource
{
    protected static ?string $model = SuspensionType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::HR_MANAGEMENT;

    public static function form(Schema $schema): Schema
    {
        return SuspensionTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SuspensionTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuspensionTypesTable::configure($table);
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
            'index' => ListSuspensionTypes::route('/'),
            'create' => CreateSuspensionType::route('/create'),
            'view' => ViewSuspensionType::route('/{record}'),
            'edit' => EditSuspensionType::route('/{record}/edit'),
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
