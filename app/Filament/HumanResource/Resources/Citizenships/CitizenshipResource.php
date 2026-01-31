<?php

namespace App\Filament\HumanResource\Resources\Citizenships;

use App\Filament\HumanResource\Resources\Citizenships\Pages\CreateCitizenship;
use App\Filament\HumanResource\Resources\Citizenships\Pages\EditCitizenship;
use App\Filament\HumanResource\Resources\Citizenships\Pages\ListCitizenships;
use App\Filament\HumanResource\Resources\Citizenships\Pages\ViewCitizenship;
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

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::HR_MANAGEMENT;

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
            'index' => ListCitizenships::route('/'),
            'create' => CreateCitizenship::route('/create'),
            'view' => ViewCitizenship::route('/{record}'),
            'edit' => EditCitizenship::route('/{record}/edit'),
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
