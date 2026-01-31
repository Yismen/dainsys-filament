<?php

namespace App\Filament\HumanResource\Resources\Supervisors;

use App\Filament\HumanResource\Resources\Supervisors\Pages\CreateSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Pages\EditSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Pages\ListSupervisors;
use App\Filament\HumanResource\Resources\Supervisors\Pages\ViewSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Schemas\SupervisorForm;
use App\Filament\HumanResource\Resources\Supervisors\Schemas\SupervisorInfolist;
use App\Filament\HumanResource\Resources\Supervisors\Tables\SupervisorsTable;
use App\Models\Scopes\IsActiveScope;
use App\Models\Supervisor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupervisorResource extends Resource
{
    protected static ?string $model = Supervisor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCamera;

    protected static ?string $recordTitleAttribute = 'name';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::HR_MANAGEMENT;

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return SupervisorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupervisorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupervisorsTable::configure($table);
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
            'index' => ListSupervisors::route('/'),
            'create' => CreateSupervisor::route('/create'),
            'view' => ViewSupervisor::route('/{record}'),
            'edit' => EditSupervisor::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                IsActiveScope::class,
            ]);
    }
}
