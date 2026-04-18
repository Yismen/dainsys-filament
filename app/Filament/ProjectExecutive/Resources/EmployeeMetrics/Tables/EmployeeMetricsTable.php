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
                    ->label(__('filament.employee'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('week_ending')
                    ->label(__('filament.week_ending'))
                    ->date('M j, Y'),
                TextColumn::make('total_time')
                    ->label(__('filament.total_login_time'))
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_production_time')
                    ->label(__('filament.total_production_time'))
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('total_billable_time')
                    ->label(__('filament.total_billable_time'))
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_conversions')
                    ->label(__('filament.total_conversions'))
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('conversions_goal')
                    ->label(__('filament.conversions_goal'))
                    ->wrapHeader()
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph')
                    ->label(__('filament.sph'))
                    ->wrapHeader()
                    ->state(function ($record) {
                        $conversions = (float) $record->total_conversions;
                        $productionHours = (float) $record->total_production_time;

                        return $productionHours > 0 ? $conversions / $productionHours : 0;
                    })
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('sph_percentage')
                    ->label(__('filament.sph_percentage'))
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
                    ->label(__('filament.efficiency_rate'))
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
                    ->label(__('filament.employee'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('filament.employee'))
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
                            ->placeholder(__('filament.all_employees')),
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
