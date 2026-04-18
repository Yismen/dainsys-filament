<?php

namespace App\Filament\HumanResource\Resources\Suspensions\Tables;

use App\Enums\SuspensionStatuses;
use App\Exports\Filament\SuspensionExporter;
use App\Models\SuspensionType;
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

class SuspensionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('starts_at', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('suspensionType.name')
                    ->label(__('filament.suspension_type'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->label(__('filament.starts_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label(__('filament.ends_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make(name: 'status')
                    ->label(__('filament.status'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('comment')
                    ->label(__('filament.comment'))
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn (string $state) => $state),
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
                Filter::make('starts_at')
                    ->label(__('filament.starts_at_range'))
                    ->schema([
                        DatePicker::make('starts_at_from')
                            ->label(__('filament.starts_at_from')),
                        DatePicker::make('starts_at_until')
                            ->label(__('filament.starts_at_until')),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['starts_at_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('starts_at', '>=', $date),
                            )
                            ->when(
                                $data['starts_at_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('starts_at', '<=', $date),
                            );
                    }),
                SelectFilter::make('suspension_type_id')
                    ->label(__('filament.Suspension Type'))
                    ->options(ModelListService::make(SuspensionType::query()))
                    ->searchable(),
                SelectFilter::make('status')
                    ->label(__('filament.Status'))
                    ->options(SuspensionStatuses::class)
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->color(Color::Teal)
                    ->exporter(SuspensionExporter::class)
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
