<?php

namespace App\Filament\HumanResource\Resources\Suspensions;

use App\Filament\HumanResource\Resources\Suspensions\Pages\CreateSuspension;
use App\Filament\HumanResource\Resources\Suspensions\Pages\EditSuspension;
use App\Filament\HumanResource\Resources\Suspensions\Pages\ListSuspensions;
use App\Filament\HumanResource\Resources\Suspensions\Pages\ViewSuspension;
use App\Filament\HumanResource\Resources\Suspensions\Schemas\SuspensionForm;
use App\Filament\HumanResource\Resources\Suspensions\Schemas\SuspensionInfolist;
use App\Filament\HumanResource\Resources\Suspensions\Tables\SuspensionsTable;
use App\Models\Suspension;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuspensionResource extends Resource
{
    protected static ?string $model = Suspension::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPause;

    protected static ?string $recordTitleAttribute = 'starts_at';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::EMPLOYEES_MANAGEMENT;

    protected static ?int $navigationSort = 2;

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record ? $record->employee->full_name : static::getModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return SuspensionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SuspensionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuspensionsTable::configure($table);
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
            'index' => ListSuspensions::route('/'),
            'create' => CreateSuspension::route('/create'),
            'view' => ViewSuspension::route('/{record}'),
            'edit' => EditSuspension::route('/{record}/edit'),
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
