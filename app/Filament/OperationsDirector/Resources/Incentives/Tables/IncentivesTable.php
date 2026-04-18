<?php

namespace App\Filament\OperationsDirector\Resources\Incentives\Tables;

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

class IncentivesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('payable_date', 'desc')
            ->columns([
                TextColumn::make('payable_date')
                    ->label(__('filament.payable_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_production_hours')
                    ->label(__('filament.total_production_hours'))
                    ->wrapHeader()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sales')
                    ->label(__('filament.total_sales'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('filament.amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('notes')
                    ->label(__('filament.notes'))
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
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
                            ->label(__('filament.payable_date_from')),
                        DatePicker::make('payable_date_until')
                            ->label(__('filament.payable_date_until')),
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
                    ->label(__('filament.payable_date'))
                    ->options(fn (): array => Incentive::query()
                        ->distinct()
                        ->orderBy('payable_date', 'desc')
                        ->pluck('payable_date', 'payable_date')
                        ->toArray()
                    )
                    ->searchable(),
                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                SelectFilter::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
