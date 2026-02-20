<?php

namespace App\Filament\Supervisor\Resources\EmployeeMetrics\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeMetricsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('total_production_time')
                    ->wrapHeader()
                    ->label('Total Production Time')
                    ->state(function ($record) {
                        return $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('production_time');
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_conversions')
                    ->label('Total Conversions')
                    ->wrapHeader()
                    ->state(function ($record) {
                        return $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('conversions');
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('conversions_goal')
                    ->label('Conversions Goal')
                    ->wrapHeader()
                    ->state(function ($record) {
                        return $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('conversions_goal');
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph')
                    ->label('SPH')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('conversions');

                        $productionHours = $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('production_time');

                        return $productionHours > 0 ? $conversions / ($productionHours) : 0;
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph_percentage')
                    ->label('SPH % to Goal')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('conversions');

                        $conversionsGoal = $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('conversions_goal');

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
                        $totalHours = $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('total_time');

                        $billableHours = $record->productions()
                            ->when(
                                session('metrics_date_from'),
                                fn ($q, $date) => $q->whereDate('date', '>=', $date)
                            )
                            ->when(
                                session('metrics_date_until'),
                                fn ($q, $date) => $q->whereDate('date', '<=', $date)
                            )
                            ->sum('billable_time');

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
                Filter::make('date')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date from'),
                        DatePicker::make('date_until')
                            ->label('Date until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        session(['metrics_date_from' => $data['date_from'], 'metrics_date_until' => $data['date_until']]);

                        return $query;
                    }),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->paginated([10, 25, 50, 100]);
    }
}
