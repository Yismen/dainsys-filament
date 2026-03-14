<?php

namespace App\Filament\Employee\Pages;

use App\Models\Production;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyMetrics extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'My Metrics';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'My Metrics';

    protected static ?string $slug = 'my-metrics';

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->employee_id) {
            abort(403, 'No employee record found.');
        }
    }

    public function getView(): string
    {
        return 'filament.pages.table-page';
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('week_ending', 'desc')
            ->defaultKeySort(false)
            ->query($this->queryInstance())
            ->columns([
                TextColumn::make('week_ending')
                    ->label('Week Ending')
                    ->date('M j, Y'),
                TextColumn::make('total_time')
                    ->wrapHeader()
                    ->label('Total Login Time')
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_production_time')
                    ->wrapHeader()
                    ->label('Total Production Time')
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_conversions')
                    ->label('Total Conversions')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('conversions_goal')
                    ->label('Conversions Goal')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph')
                    ->label('SPH')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = $record->total_conversions;
                        $productionHours = $record->total_production_time;

                        return $productionHours > 0 ? $conversions / $productionHours : 0;
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph_percentage')
                    ->label('SPH % to Goal')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = $record->total_conversions;
                        $conversionsGoal = $record->conversions_goal;

                        return $conversionsGoal > 0 ? ($conversions / $conversionsGoal) * 100 : 0;
                    })
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 100) {
                            return 'success';
                        } elseif ($state >= 80) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),
                TextColumn::make('efficiency_rate')
                    ->label('Efficiency Rate %')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $totalHours = $record->total_time;
                        $billableHours = $record->total_billable_time;

                        return $totalHours > 0 ? ($billableHours / $totalHours) * 100 : 0;
                    })
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 100) {
                            return 'success';
                        } elseif ($state >= 90) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),
            ])
            ->filters([
            ]);
    }

    protected function queryInstance(): Builder
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
                SUM(productions.total_time) as total_time,
                SUM(productions.production_time) as total_production_time,
                SUM(productions.conversions) as total_conversions,
                SUM(productions.conversions_goal) as conversions_goal,
                SUM(productions.billable_time) as total_billable_time
            ")
            ->join('employees', 'productions.employee_id', '=', 'employees.id')
            ->where('productions.employee_id', $employee->id)
            ->where('productions.date', '>=', $eightWeeksAgo)
            ->groupByRaw("{$weekGroupingExpression}, productions.employee_id");
    }

    protected static function getWeekGroupingExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "date(productions.date, '+' || ((7 - CAST(strftime('%w', productions.date) AS integer)) % 7) || ' days')",
            default => 'DATE_ADD(productions.date, INTERVAL (6 - WEEKDAY(productions.date)) DAY)',
        };
    }
}
