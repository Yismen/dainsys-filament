<?php

namespace App\Filament\Workforce\Resources\Productions\Tables;

use App\Enums\RevenueTypes;
use App\Filament\Imports\ProductionImporter;
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
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductionImporter::class)
                    ->color(Color::Indigo)
                    ->icon(Heroicon::ArrowUpTray),
            ])
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('revenue_type')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('revenue_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sph_goal')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('conversions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('production_time')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('talk_time')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('billable_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('revenue')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('converted_to_payroll_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
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
            ->filters([
                Filter::make('date')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Date from'),
                        DatePicker::make('date_until')
                            ->label('Date until'),
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
                    ->label('Employee')
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label('Campaign')
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable(),
                SelectFilter::make('revenue_type')
                    ->label('Revenue Type')
                    ->options(RevenueTypes::class)
                    ->searchable(),
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->query(function ($query, $value) {
                        return $query->when($value, function ($query, $value) {
                            $query->whereHas('campaign', function ($query) use ($value) {
                                $query->where('project_id', $value);
                            });
                        });
                    }),
                SelectFilter::make('supervisor_id')
                    ->label('Supervisor')
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
