<?php

namespace App\Filament\ProjectExecutive\Resources\Downtimes;

use App\Filament\ProjectExecutive\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\ProjectExecutive\Resources\Downtimes\Tables\DowntimesTable;
use App\Models\Downtime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DowntimeResource extends Resource
{
    protected static ?string $model = Downtime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 6;

    public static function table(Table $table): Table
    {
        return DowntimesTable::configure($table);
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
            'index' => ListDowntimes::route('/'),
        ];
    }
}
