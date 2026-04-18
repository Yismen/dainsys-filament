<?php

namespace App\Filament\Workforce\Resources\Productions\Tables;

use App\Actions\Filament\PayrollHour\UpdatePayrollHoursAction;
use App\Enums\RevenueTypes;
use App\Imports\Filament\ProductionImporter;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->filtersFormColumns(2)
            ->defaultSort('date', 'DESC')
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductionImporter::class)
                    ->color(Color::Indigo)
                    ->icon(Heroicon::ArrowUpTray),
                UpdatePayrollHoursAction::make(),
            ])
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->wrap()
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->wrap()
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->limit(25)
                    ->tooltip(fn ($state, $record) => $record->campaign?->name),
                TextColumn::make('campaign.project.name')
                    ->label(__('filament.project'))
                    ->wrap()
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('revenue_type')
                    ->label(__('filament.revenue_type'))
                    ->wrap()
                    ->sortable()
                    ->wrapHeader()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sph_goal')
                    ->label(__('filament.sph_goal'))
                    ->numeric()
                    ->wrapHeader()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_time')
                    ->label(__('filament.total_time'))
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('production_time')
                    ->label(__('filament.production_time'))
                    ->numeric()
                    ->wrapHeader()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('talk_time')
                    ->label(__('filament.talk_time'))
                    ->numeric()
                    ->wrapHeader()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('billable_time')
                    ->label(__('filament.billable_time'))
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('conversions')
                    ->label(__('filament.sales'))
                    ->numeric()
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('revenue_rate')
                    ->label(__('filament.revenue_rate'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('revenue')
                    ->label(__('filament.revenue'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('converted_to_payroll_at')
                    ->label(__('filament.converted_to_payroll'))
                    ->dateTime()
                    ->wrapHeader()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->sortable()
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
            ->filters([
                Filter::make('date')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label(__('filament.date_from')),
                        DatePicker::make('date_until')
                            ->label(__('filament.date_until')),
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
                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label(__('filament.campaign'))
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable(),
                SelectFilter::make('revenue_type')
                    ->label(__('filament.revenue_type'))
                    ->options(RevenueTypes::class)
                    ->searchable(),
                SelectFilter::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->query(function ($query, $data): void {
                        $value = $data['value'] ?? null;

                        $query->when($value, function ($query, $value): void {
                            $query->whereHas('campaign', function ($query) use ($value): void {
                                $query->where('project_id', $value);
                            });
                        });
                    }),
                SelectFilter::make('supervisor_id')
                    ->label(__('filament.supervisor'))
                    ->options(ModelListService::make(Supervisor::query()))
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
