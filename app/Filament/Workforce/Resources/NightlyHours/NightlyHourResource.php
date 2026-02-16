<?php

namespace App\Filament\Workforce\Resources\NightlyHours;

use App\Filament\Workforce\Resources\NightlyHours\Pages\CreateNightlyHour;
use App\Filament\Workforce\Resources\NightlyHours\Pages\EditNightlyHour;
use App\Filament\Workforce\Resources\NightlyHours\Pages\ListNightlyHours;
use App\Filament\Workforce\Resources\NightlyHours\Pages\ViewNightlyHour;
use App\Filament\Workforce\Resources\NightlyHours\Schemas\NightlyHourForm;
use App\Filament\Workforce\Resources\NightlyHours\Schemas\NightlyHourInfolist;
use App\Filament\Workforce\Resources\NightlyHours\Tables\NightlyHoursTable;
use App\Models\NightlyHour;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NightlyHourResource extends Resource
{
    protected static ?string $model = NightlyHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMoon;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return NightlyHourForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NightlyHourInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NightlyHoursTable::configure($table);
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
            'index' => ListNightlyHours::route('/'),
            // 'create' => CreateNightlyHour::route('/create'),
            // 'view' => ViewNightlyHour::route('/{record}'),
            // 'edit' => EditNightlyHour::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('interactsWithWorkforce');
    }
}
