<?php

namespace App\Filament\Workforce\Resources\LoginNames;

use App\Filament\Workforce\Resources\LoginNames\Pages\CreateLoginName;
use App\Filament\Workforce\Resources\LoginNames\Pages\EditLoginName;
use App\Filament\Workforce\Resources\LoginNames\Pages\ListLoginNames;
use App\Filament\Workforce\Resources\LoginNames\Pages\ViewLoginName;
use App\Filament\Workforce\Resources\LoginNames\Schemas\LoginNameForm;
use App\Filament\Workforce\Resources\LoginNames\Schemas\LoginNameInfolist;
use App\Filament\Workforce\Resources\LoginNames\Tables\LoginNamesTable;
use App\Models\LoginName;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoginNameResource extends Resource
{
    protected static ?string $model = LoginName::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $recordTitleAttribute = 'login_name';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return LoginNameForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LoginNameInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoginNamesTable::configure($table);
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
            'index' => ListLoginNames::route('/'),
            'create' => CreateLoginName::route('/create'),
            'view' => ViewLoginName::route('/{record}'),
            'edit' => EditLoginName::route('/{record}/edit'),
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
