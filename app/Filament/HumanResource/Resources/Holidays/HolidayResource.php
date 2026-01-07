<?php

namespace App\Filament\HumanResource\Resources\Holidays;

use BackedEnum;
use App\Models\Holiday;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Resources\Holidays\Pages\EditHoliday;
use App\Filament\HumanResource\Resources\Holidays\Pages\ViewHoliday;
use App\Filament\HumanResource\Resources\Holidays\Pages\ListHolidays;
use App\Filament\HumanResource\Resources\Holidays\Pages\CreateHoliday;
use App\Filament\HumanResource\Resources\Holidays\Schemas\HolidayForm;
use App\Filament\HumanResource\Resources\Holidays\Tables\HolidaysTable;
use App\Filament\HumanResource\Clusters\HrManagement\HrManagementCluster;
use App\Filament\HumanResource\Resources\Holidays\Schemas\HolidayInfolist;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = HrManagementCluster::class;

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return HolidayForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HolidayInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HolidaysTable::configure($table);
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
            'index' => ListHolidays::route('/'),
            'create' => CreateHoliday::route('/create'),
            'view' => ViewHoliday::route('/{record}'),
            'edit' => EditHoliday::route('/{record}/edit'),
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
