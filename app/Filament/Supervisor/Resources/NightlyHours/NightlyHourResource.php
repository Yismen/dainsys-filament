<?php

namespace App\Filament\Supervisor\Resources\NightlyHours;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\NightlyHours\Pages\ListNightlyHours;
use App\Filament\Supervisor\Resources\NightlyHours\Tables\NightlyHoursTable;
use App\Models\NightlyHour;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class NightlyHourResource extends Resource
{
    protected static ?string $model = NightlyHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMoon;

    protected static ?string $navigationLabel = 'Nightly Hours';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return NightlyHoursTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id)
                    ->whereIn('status', [EmployeeStatuses::Hired, EmployeeStatuses::Suspended]);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNightlyHours::route('/'),
        ];
    }
}
