<?php

namespace App\Filament\Supervisor\Resources\PayrollHours;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours;
use App\Filament\Supervisor\Resources\PayrollHours\Tables\PayrollHoursTable;
use App\Models\PayrollHour;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PayrollHourResource extends Resource
{
    protected static ?string $model = PayrollHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Payroll Hours';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return PayrollHoursTable::configure($table);
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
            'index' => ListPayrollHours::route('/'),
        ];
    }
}
