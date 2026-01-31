<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities;

use App\Filament\HumanResource\Resources\SocialSecurities\Pages\CreateSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\EditSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ListSocialSecurities;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ViewSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Schemas\SocialSecurityForm;
use App\Filament\HumanResource\Resources\SocialSecurities\Schemas\SocialSecurityInfolist;
use App\Filament\HumanResource\Resources\SocialSecurities\Tables\SocialSecuritiesTable;
use App\Models\SocialSecurity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialSecurityResource extends Resource
{
    protected static ?string $model = SocialSecurity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 10;

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::EMPLOYEES_MANAGEMENT;

    public static function form(Schema $schema): Schema
    {
        return SocialSecurityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SocialSecurityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialSecuritiesTable::configure($table);
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
            'index' => ListSocialSecurities::route('/'),
            'create' => CreateSocialSecurity::route('/create'),
            'view' => ViewSocialSecurity::route('/{record}'),
            'edit' => EditSocialSecurity::route('/{record}/edit'),
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
