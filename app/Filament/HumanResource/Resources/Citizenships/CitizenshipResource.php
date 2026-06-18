<?php

namespace App\Filament\HumanResource\Resources\Citizenships;

use App\Filament\HumanResource\Enums\HRNavigationEnum;
use App\Filament\HumanResource\Resources\Citizenships\Pages\ManageCitizenships;
use App\Filament\HumanResource\Resources\Citizenships\Schemas\CitizenshipForm;
use App\Filament\HumanResource\Resources\Citizenships\Schemas\CitizenshipInfolist;
use App\Filament\HumanResource\Resources\Citizenships\Tables\CitizenshipsTable;
use App\Models\Citizenship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CitizenshipResource extends Resource
{
    protected static ?string $model = Citizenship::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static \UnitEnum|string|null $navigationGroup = HRNavigationEnum::HR_MANAGEMENT;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return CitizenshipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CitizenshipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitizenshipsTable::configure($table);
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
            'index' => ManageCitizenships::route('/'),
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
