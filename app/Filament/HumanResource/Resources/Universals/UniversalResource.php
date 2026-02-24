<?php

namespace App\Filament\HumanResource\Resources\Universals;

use App\Filament\HumanResource\Resources\Universals\Pages\CreateUniversal;
use App\Filament\HumanResource\Resources\Universals\Pages\EditUniversal;
use App\Filament\HumanResource\Resources\Universals\Pages\ListUniversals;
use App\Filament\HumanResource\Resources\Universals\Pages\ViewUniversal;
use App\Filament\HumanResource\Resources\Universals\Schemas\UniversalForm;
use App\Filament\HumanResource\Resources\Universals\Schemas\UniversalInfolist;
use App\Filament\HumanResource\Resources\Universals\Tables\UniversalsTable;
use App\Models\Universal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UniversalResource extends Resource
{
    protected static ?string $model = Universal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    // protected static ?string $recordTitleAttribute = 'employee.full_name';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::EMPLOYEES_MANAGEMENT;

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return UniversalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UniversalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UniversalsTable::configure($table);
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
            'index' => ListUniversals::route('/'),
            'create' => CreateUniversal::route('/create'),
            'view' => ViewUniversal::route('/{record}'),
            'edit' => EditUniversal::route('/{record}/edit'),
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
