<?php

namespace App\Filament\Workforce\Resources\Sources;

use App\Filament\Workforce\Resources\Sources\Pages\CreateSource;
use App\Filament\Workforce\Resources\Sources\Pages\EditSource;
use App\Filament\Workforce\Resources\Sources\Pages\ListSources;
use App\Filament\Workforce\Resources\Sources\Pages\ViewSource;
use App\Filament\Workforce\Resources\Sources\Schemas\SourceForm;
use App\Filament\Workforce\Resources\Sources\Schemas\SourceInfolist;
use App\Filament\Workforce\Resources\Sources\Tables\SourcesTable;
use App\Models\Source;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SourceResource extends Resource
{
    protected static ?string $model = Source::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SourceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SourcesTable::configure($table);
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
            'index' => ListSources::route('/'),
            'create' => CreateSource::route('/create'),
            'view' => ViewSource::route('/{record}'),
            'edit' => EditSource::route('/{record}/edit'),
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
