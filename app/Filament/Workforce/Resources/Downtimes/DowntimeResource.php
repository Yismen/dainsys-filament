<?php

namespace App\Filament\Workforce\Resources\Downtimes;

use App\Filament\Workforce\Resources\Downtimes\Pages\CreateDowntime;
use App\Filament\Workforce\Resources\Downtimes\Pages\EditDowntime;
use App\Filament\Workforce\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\Workforce\Resources\Downtimes\Pages\ViewDowntime;
use App\Filament\Workforce\Resources\Downtimes\Schemas\DowntimeForm;
use App\Filament\Workforce\Resources\Downtimes\Schemas\DowntimeInfolist;
use App\Filament\Workforce\Resources\Downtimes\Tables\DowntimesTable;
use App\Models\Downtime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DowntimeResource extends Resource
{
    protected static ?string $model = Downtime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownOnSquareStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Imports';

    public static function form(Schema $schema): Schema
    {
        return DowntimeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DowntimeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DowntimesTable::configure($table);
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
            'index' => ListDowntimes::route('/'),
            // 'create' => CreateDowntime::route('/create'),
            // 'view' => ViewDowntime::route('/{record}'),
            // 'edit' => EditDowntime::route('/{record}/edit'),
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
