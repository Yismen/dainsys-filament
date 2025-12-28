<?php

namespace App\Filament\HumanResource\Resources\Afps;

use App\Filament\HumanResource\Resources\Afps\Pages\CreateAfp;
use App\Filament\HumanResource\Resources\Afps\Pages\EditAfp;
use App\Filament\HumanResource\Resources\Afps\Pages\ListAfps;
use App\Filament\HumanResource\Resources\Afps\Pages\ViewAfp;
use App\Filament\HumanResource\Resources\Afps\Schemas\AfpForm;
use App\Filament\HumanResource\Resources\Afps\Schemas\AfpInfolist;
use App\Filament\HumanResource\Resources\Afps\Tables\AfpsTable;
use App\Models\Afp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AfpResource extends Resource
{
    protected static ?string $model = Afp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

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
