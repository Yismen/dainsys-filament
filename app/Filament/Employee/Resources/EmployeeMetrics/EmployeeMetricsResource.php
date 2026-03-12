<?php

namespace App\Filament\Employee\Resources\EmployeeMetrics;

use App\Enums\EmployeeStatuses;
use App\Filament\Employee\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics;
use App\Filament\Employee\Resources\EmployeeMetrics\Tables\EmployeeMetricsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class EmployeeMetricsResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'My Metrics';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'My Metrics';

    protected static ?string $slug = 'my-metrics';

    public static function table(Table $table): Table
    {
        return EmployeeMetricsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $employee = Auth::user()?->employee;

        if (! $employee) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $eightWeeksAgo = now()->subWeeks(8);
        $weekGroupingExpression = static::getWeekGroupingExpression();

        return Production::query()
            ->selectRaw("
                MIN(productions.id) as id,
                {$weekGroupingExpression} as week_ending,
                productions.employee_id,
                SUM(productions.production_time) as total_production_time,
                SUM(productions.conversions) as total_conversions,
                SUM(productions.conversions_goal) as conversions_goal,
                SUM(productions.billable_time) as total_billable_time,
                SUM(productions.total_time) as total_time
            ")
            ->join('employees', 'productions.employee_id', '=', 'employees.id')
            ->where('productions.employee_id', $employee->id)
            ->where('productions.date', '>=', $eightWeeksAgo)
            ->whereIn('employees.status', [EmployeeStatuses::Hired, EmployeeStatuses::Suspended])
            ->groupByRaw("{$weekGroupingExpression}, productions.employee_id");
    }

    protected static function getWeekGroupingExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "date(productions.date, '+' || ((7 - CAST(strftime('%w', productions.date) AS integer)) % 7) || ' days')",
            default => 'DATE_ADD(productions.date, INTERVAL (6 - WEEKDAY(productions.date)) DAY)',
        };
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployeeMetrics::route('/'),
        ];
    }
}
