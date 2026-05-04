<?php

namespace App\Filament\HumanResource\Resources\Hires\Tables;

use App\Exports\Filament\HireExporter;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
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

class HiresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('site.name')
                    ->label(__('filament.site'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('position.details')
                    ->label(__('filament.position'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('supervisor.name')
                    ->label(__('filament.supervisor'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
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
                    ->label(__('filament.date_range'))
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
                SelectFilter::make('site_id')
                    ->label(__('filament.Site'))
                    ->options(ModelListService::make(Site::query()))
                    ->searchable(),
                SelectFilter::make('project_id')
                    ->label(__('filament.Project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
                SelectFilter::make('position_id')
                    ->label(__('filament.Position'))
                    ->options(ModelListService::make(Position::query()))
                    ->searchable(),
                SelectFilter::make('supervisor_id')
                    ->label(__('filament.Supervisor'))
                    ->options(ModelListService::make(Supervisor::query()))
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->color(Color::Teal)
                    ->exporter(HireExporter::class)
                    ->deselectRecordsAfterCompletion()
                    ->icon(Heroicon::OutlinedDocumentArrowDown),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
