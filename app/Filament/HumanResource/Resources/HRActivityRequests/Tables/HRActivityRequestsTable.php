<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Tables;

use App\Enums\HRActivityRequestStatuses;
use App\Models\HRActivityRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HRActivityRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supervisor.full_name')
                    ->label(__('filament.supervisor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('activity_type')
                    ->label(__('filament.activity_type'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('requested_at')
                    ->label(__('filament.requested_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label(__('filament.completed_at'))
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
                SelectFilter::make('status')
                    ->options(HRActivityRequestStatuses::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
                Action::make('complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== HRActivityRequestStatuses::Completed)
                    ->Schema([
                        Textarea::make('comment')
                            ->required()
                            ->label(__('filament.completion_comment'))
                            ->placeholder('Add a comment about this completion...')
                            ->rows(3),
                    ])
                    ->action(function (HRActivityRequest $record, array $data): void {
                        $record->markAsCompleted($data['comment']);
                    })
                    ->successNotificationTitle('Request marked as completed'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('requested_at', 'desc');
    }
}
