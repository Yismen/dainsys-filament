<?php

namespace App\Filament\Supervisor\Resources\PayrollHours\Tables;

use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                        Sum::make()
                    ]),
                TextColumn::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                    ]),
                TextColumn::make('holiday_hours')
                    ->label('Holiday Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                    ]),
                TextColumn::make('seventh_day_hours')
                    ->label('Seventh Day Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                    ]),
                TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                    ]),
            ])
            ->filters([
                Filter::make('date')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date from')
                            ->default(now()->startOfMonth()->startOfDay()),
                        DatePicker::make('date_until')
                            ->label('Date until')
                            ->default(now()->endOfDay()),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
                Filter::make('payroll_ending_at')
                    ->label('Week Ending')
                    ->schema([
                        DatePicker::make('payroll_ending_at'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payroll_ending_at'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payroll_ending_at', '=', $date),
                            );
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
