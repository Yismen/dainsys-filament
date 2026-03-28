<?php

namespace App\Filament\ProjectExecutive\Resources\Incentives\Tables;

use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IncentivesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('payable_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_production_hours')
                    ->wrapHeader()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sales')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('notes')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::TwoExtraLarge)
            ->filters([
                Filter::make('payable_date_range')
                    ->schema([
                        DatePicker::make('payable_date_from')
                            ->label('Payable date from'),
                        DatePicker::make('payable_date_until')
                            ->label('Payable date until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payable_date_from'],
                                fn (Builder $builder, $date): Builder => $builder->whereDate('payable_date', '>=', $date),
                            )
                            ->when(
                                $data['payable_date_until'],
                                fn (Builder $builder, $date): Builder => $builder->whereDate('payable_date', '<=', $date),
                            );
                    }),
                SelectFilter::make('payable_date')
                    ->label('Payable date')
                    ->options(function (): array {
                        $managerId = Auth::id();

                        return Incentive::query()
                            ->whereHas('employee.project', fn (Builder $query) => $query->where('manager_id', $managerId))
                            ->distinct()
                            ->orderBy('payable_date', 'desc')
                            ->pluck('payable_date', 'payable_date')
                            ->toArray();
                    })
                    ->searchable(),
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(function (): array {
                        $managerId = Auth::id();

                        return ModelListService::make(
                            Employee::query()
                                ->whereHas('project', fn (Builder $query) => $query->where('manager_id', $managerId)),
                            value_field: 'full_name'
                        );
                    })
                    ->searchable(),
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(function (): array {
                        $managerId = Auth::id();

                        return ModelListService::make(
                            Project::query()->where('manager_id', $managerId)
                        );
                    })
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
