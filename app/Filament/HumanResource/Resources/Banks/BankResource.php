<?php

namespace App\Filament\HumanResource\Resources\Banks;

use App\Filament\HumanResource\Resources\Banks\Pages\CreateBank;
use App\Filament\HumanResource\Resources\Banks\Pages\EditBank;
use App\Filament\HumanResource\Resources\Banks\Pages\ListBanks;
use App\Filament\HumanResource\Resources\Banks\Pages\ViewBank;
use App\Filament\HumanResource\Resources\Banks\Schemas\BankForm;
use App\Filament\HumanResource\Resources\Banks\Schemas\BankInfolist;
use App\Filament\HumanResource\Resources\Banks\Tables\BanksTable;
use App\Models\Bank;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::HR_MANAGEMENT;

    public static function form(Schema $schema): Schema
    {
        return BankForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BankInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BanksTable::configure($table);
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
            'index' => ListBanks::route('/'),
            'create' => CreateBank::route('/create'),
            'view' => ViewBank::route('/{record}'),
            'edit' => EditBank::route('/{record}/edit'),
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
