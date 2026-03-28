<?php

namespace App\Filament\ProjectExecutive\Resources\EmployeeMetrics;

use App\Enums\EmployeeStatuses;
use App\Filament\ProjectExecutive\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics;
use App\Filament\ProjectExecutive\Resources\EmployeeMetrics\Tables\EmployeeMetricsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeMetricsResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Metrics';

    protected static ?string $modelLabel = 'Employee Metrics';

    protected static ?string $slug = 'metrics';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return EmployeeMetricsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $eightWeeksAgo = now()->subWeeks(8);
        $weekGroupingExpression = static::getWeekGroupingExpression();

        return Production::query()
            ->selectRaw("
                MIN(productions.id) as id,
                {$weekGroupingExpression} as week_ending,
                productions.employee_id,
                employees.full_name,
                SUM(productions.total_time) as total_time,
                SUM(productions.production_time) as total_production_time,
                SUM(productions.conversions) as total_conversions,
                SUM(productions.conversions_goal) as conversions_goal,
                SUM(productions.billable_time) as total_billable_time
            ")
            ->join('employees', 'productions.employee_id', '=', 'employees.id')
            ->join('projects', 'employees.project_id', '=', 'projects.id')
            ->where('projects.manager_id', $managerId)
            ->where('productions.date', '>=', $eightWeeksAgo)
            ->whereIn('employees.status', [EmployeeStatuses::Hired, EmployeeStatuses::Suspended])
            ->groupByRaw("{$weekGroupingExpression}, productions.employee_id, employees.full_name");
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
