<?php

namespace App\Filament\Supervisor\Resources\PayrollHours\Tables;

use App\Models\Employee;
use App\Models\PayrollHour;
use App\Services\ModelListService;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PayrollHoursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('regular_hours')
                    ->label('Regular Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                    ]),
                TextColumn::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                    ]),
                TextColumn::make('holiday_hours')
                    ->label('Holiday Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                    ]),
                TextColumn::make('seventh_day_hours')
                    ->label('7th Day Hours')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                    ]),
                TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                    ]),
            ])
            ->filters([
                SelectFilter::make('payroll_ending_at')
                    ->options(function () {
                        return Cache::rememberForever('payroll_ending_at_dates', function () {
                            return PayrollHour::query()
                                ->select('payroll_ending_at')
                                ->distinct()
                                ->orderBy('payroll_ending_at', 'desc')
                                ->pluck('payroll_ending_at')
                                ->mapWithKeys(fn ($date) => [
                                    $date->toDateString() => $date->toFormattedDateString(),
                                ]);
                        });
                    }),
                SelectFilter::make('employee_id')
                    ->options(ModelListService::make(
                        model: Employee::query()
                            ->active()
                            ->whereHas('supervisor', function (Builder $query): void {
                                $query->where('id', Auth::user()?->supervisor?->id);
                            }),
                        value_field: 'full_name',
                    ))
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->paginated([10, 25, 50, 100]);
    }
}
