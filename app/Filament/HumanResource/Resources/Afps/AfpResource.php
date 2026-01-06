<?php

namespace App\Filament\HumanResource\Resources\Afps;

use BackedEnum;
use App\Models\Afp;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Clusters\TSS\TSSCluster;
use App\Filament\HumanResource\Resources\Afps\Pages\EditAfp;
use App\Filament\HumanResource\Resources\Afps\Pages\ViewAfp;
use App\Filament\HumanResource\Resources\Afps\Pages\ListAfps;
use App\Filament\HumanResource\Resources\Afps\Pages\CreateAfp;
use App\Filament\HumanResource\Resources\Afps\Schemas\AfpForm;
use App\Filament\HumanResource\Resources\Afps\Tables\AfpsTable;
use App\Filament\HumanResource\Resources\Afps\Schemas\AfpInfolist;

class AfpResource extends Resource
{
    protected static ?string $model = Afp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = TSSCluster::class;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return AfpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AfpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AfpsTable::configure($table);
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
            'index' => ListAfps::route('/'),
            'create' => CreateAfp::route('/create'),
            'view' => ViewAfp::route('/{record}'),
            'edit' => EditAfp::route('/{record}/edit'),
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
