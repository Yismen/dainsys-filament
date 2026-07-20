<?php

namespace App\Filament\HumanResource\Resources\Terminations\Tables;

use App\Exports\Filament\TerminationExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TerminationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('termination_type')
                    ->label(__('filament.termination_type'))
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => __('enums.termination.'.$state))
                    ->searchable(),
                TextColumn::make('comment')
                    ->label(__('filament.comment'))
                    ->limit(45)
                    ->tooltip(fn (string $state) => $state)
                    ->sortable(),
                IconColumn::make('is_rehireable')
                    ->label(__('filament.is_rehirable'))
                    ->sortable()
                    ->boolean(),
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
                SelectFilter::make('is_rehireable')
                    ->label(__('filament.is_rehirable'))
                    ->options([
                        '1' => __('filament.rehireable'),
                        '0' => __('filament.not_rehireable'),
                    ]),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->color(Color::Teal)
                    ->exporter(TerminationExporter::class)
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
