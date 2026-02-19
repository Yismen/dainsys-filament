<?php

namespace App\Filament\Workforce\Resources\Dispositions;

use App\Filament\Workforce\Resources\Dispositions\Pages\CreateDisposition;
use App\Filament\Workforce\Resources\Dispositions\Pages\EditDisposition;
use App\Filament\Workforce\Resources\Dispositions\Pages\ListDispositions;
use App\Filament\Workforce\Resources\Dispositions\Pages\ViewDisposition;
use App\Filament\Workforce\Resources\Dispositions\Schemas\DispositionForm;
use App\Filament\Workforce\Resources\Dispositions\Schemas\DispositionInfolist;
use App\Filament\Workforce\Resources\Dispositions\Tables\DispositionsTable;
use App\Models\Disposition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DispositionResource extends Resource
{
    protected static ?string $model = Disposition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'APIs';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return DispositionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DispositionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispositionsTable::configure($table);
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
            'index' => ListDispositions::route('/'),
            // 'create' => CreateDisposition::route('/create'),
            // 'view' => ViewDisposition::route('/{record}'),
            // 'edit' => EditDisposition::route('/{record}/edit'),
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
