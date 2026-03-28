<?php

namespace App\Filament\ProjectExecutive\Resources\Absences;

use App\Filament\ProjectExecutive\Resources\Absences\Pages\ListAbsences;
use App\Filament\ProjectExecutive\Resources\Absences\Tables\AbsencesTable;
use App\Models\Absence;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AbsenceResource extends Resource
{
    protected static ?string $model = Absence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return AbsencesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee.project', function (Builder $query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAbsences::route('/'),
        ];
    }
}
