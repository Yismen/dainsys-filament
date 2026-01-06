<?php

namespace App\Filament\HumanResource\Resources\Ars;

use BackedEnum;
use App\Models\Ars;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Clusters\TSS\TSSCluster;
use App\Filament\HumanResource\Resources\Ars\Pages\EditArs;
use App\Filament\HumanResource\Resources\Ars\Pages\ListArs;
use App\Filament\HumanResource\Resources\Ars\Pages\ViewArs;
use App\Filament\HumanResource\Resources\Ars\Pages\CreateArs;
use App\Filament\HumanResource\Resources\Ars\Schemas\ArsForm;
use App\Filament\HumanResource\Resources\Ars\Tables\ArsTable;
use App\Filament\HumanResource\Resources\Ars\Schemas\ArsInfolist;

class ArsResource extends Resource
{
    protected static ?string $model = Ars::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = TSSCluster::class;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ArsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ArsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArsTable::configure($table);
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
            'index' => ListArs::route('/'),
            'create' => CreateArs::route('/create'),
            'view' => ViewArs::route('/{record}'),
            'edit' => EditArs::route('/{record}/edit'),
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
