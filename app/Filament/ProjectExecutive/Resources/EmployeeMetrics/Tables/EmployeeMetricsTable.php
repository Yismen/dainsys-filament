<?php

namespace App\Filament\ProjectExecutive\Resources\EmployeeMetrics\Tables;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EmployeeMetricsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name', 'asc')
            ->defaultKeySort(false)
            ->defaultGroup('full_name')
            ->groups([
                Group::make('full_name'),
            ])
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('week_ending')
                    ->label('Week Ending')
                    ->date('M j, Y'),
                TextColumn::make('total_time')
                    ->label('Total Login Time')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_production_time')
                    ->label('Total Production Time')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_billable_time')
                    ->label('Total Billable Time')
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2)
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        $conversions = (float) $record->total_conversions;
                        $productionHours = (float) $record->total_production_time;

                        return $productionHours > 0 ? $conversions / $productionHours : 0;
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph_percentage')
                    ->label('SPH % to Goal')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = (float) $record->total_conversions;
                        $conversionsGoal = (float) $record->conversions_goal;

                        return $conversionsGoal > 0 ? ($conversions / $conversionsGoal) * 100 : 0;
                    })
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 100) {
                            return 'success';
                        }

                        if ($state >= 80) {
                            return 'warning';
                        }

                        return 'danger';
                    }),
                TextColumn::make('efficiency_rate')
                    ->label('Efficiency Rate %')
                    ->wrapHeader()
                    ->state(function ($record) {
                        $totalHours = (float) $record->total_time;
                        $billableHours = (float) $record->total_billable_time;

                        return $totalHours > 0 ? ($billableHours / $totalHours) * 100 : 0;
                    })
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 100) {
                            return 'success';
                        }

                        if ($state >= 90) {
                            return 'warning';
                        }

                        return 'danger';
                    }),
            ])
            ->filters([
                Filter::make('employee')
                    ->label('Employee')
                    ->schema([
                        Select::make('employee_id')
                            ->label('Employee')
                            ->options(function () {
                                $managerId = Auth::id();

                                if (! $managerId) {
                                    return [];
                                }

                                return Employee::query()
                                    ->whereHas('project', function (Builder $query) use ($managerId): void {
                                        $query->where('manager_id', $managerId);
                                    })
                                    ->whereIn('status', [
                                        EmployeeStatuses::Hired,
                                        EmployeeStatuses::Suspended,
                                    ])
                                    ->orderBy('full_name')
                                    ->pluck('full_name', 'id');
                            })
                            ->searchable()
                            ->placeholder('All Employees'),
                    ])
                    ->indicateUsing(function ($data) {
                        if (isset($data['employee_id'])) {
                            $employee = Employee::find($data['employee_id']);

                            return $employee?->full_name;
                        }

                        return null;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['employee_id'] ?? null,
                            fn (Builder $builder, $employeeId): Builder => $builder->where('productions.employee_id', $employeeId),
                        );
                    }),
            ]);
    }
}
