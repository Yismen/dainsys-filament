<?php

namespace App\Filament\Supervisor\Resources\EmployeeMetrics;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics;
use App\Filament\Supervisor\Resources\EmployeeMetrics\Tables\EmployeeMetricsTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class EmployeeMetricsResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Employee Metrics';

    protected static ?int $navigationSort = 4;

    protected static string|UnitEnum|null $navigationGroup = 'Team Insights';

    protected static ?string $slug = 'employee-metrics';

    public static function table(Table $table): Table
    {
        return EmployeeMetricsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('supervisor_id', $supervisor->id)
            ->whereIn('status', [EmployeeStatuses::Hired, EmployeeStatuses::Suspended]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployeeMetrics::route('/'),
        ];
    }
}
