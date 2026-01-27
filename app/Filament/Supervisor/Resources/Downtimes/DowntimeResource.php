<?php

namespace App\Filament\Supervisor\Resources\Downtimes;

use App\Filament\Supervisor\Resources\Downtimes\Pages\CreateDowntime;
use App\Filament\Supervisor\Resources\Downtimes\Pages\EditDowntime;
use App\Filament\Supervisor\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\Supervisor\Resources\Downtimes\Pages\ViewDowntime;
use App\Filament\Supervisor\Resources\Downtimes\Schemas\DowntimeForm;
use App\Filament\Supervisor\Resources\Downtimes\Schemas\DowntimeInfolist;
use App\Filament\Supervisor\Resources\Downtimes\Tables\DowntimesTable;
use App\Models\Downtime;
use App\Models\Supervisor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DowntimeResource extends Resource
{
    protected static ?string $model = Downtime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'My Team Downtimes';

    protected static ?string $pluralModelLabel = 'Team Downtimes';

    public static function canAccess(): bool
    {
        return Auth::user()->can('manageSupervisor');
    }

    public static function form(Schema $schema): Schema
    {
        return DowntimeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DowntimeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DowntimesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Supervisor::where('user_id', auth()->id())->first();

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        // Get employee IDs that belong to this supervisor
        $employeeIds = $supervisor->employees()->pluck('employees.id');

        return parent::getEloquentQuery()
            ->whereIn('employee_id', $employeeIds);
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
            'index' => ListDowntimes::route('/'),
            'create' => CreateDowntime::route('/create'),
            'view' => ViewDowntime::route('/{record}'),
            'edit' => EditDowntime::route('/{record}/edit'),
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
