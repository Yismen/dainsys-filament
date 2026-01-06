<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\SocialSecurity;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Clusters\TSS\TSSCluster;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\EditSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ViewSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\CreateSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ListSocialSecurities;
use App\Filament\HumanResource\Resources\SocialSecurities\Schemas\SocialSecurityForm;
use App\Filament\HumanResource\Resources\SocialSecurities\Tables\SocialSecuritiesTable;
use App\Filament\HumanResource\Resources\SocialSecurities\Schemas\SocialSecurityInfolist;

class SocialSecurityResource extends Resource
{
    protected static ?string $model = SocialSecurity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = TSSCluster::class;

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
