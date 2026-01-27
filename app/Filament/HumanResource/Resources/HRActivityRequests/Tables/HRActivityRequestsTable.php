<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Tables;

use App\Enums\HRActivityRequestStatuses;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
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
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supervisor.full_name')
                    ->label('Supervisor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('activity_type')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('requested_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
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
                SelectFilter::make('status')
                    ->options(HRActivityRequestStatuses::class)
                    ->default(HRActivityRequestStatuses::Requested->value),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== HRActivityRequestStatuses::Completed)
                    ->Schema([
                        Textarea::make('comment')
                            ->required()
                            ->label('Completion Comment')
                            ->placeholder('Add a comment about this completion...')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->markAsCompleted($data['comment']);
                    })
                    ->successNotificationTitle('Request marked as completed')
                    ->after(fn () => redirect()->back()),
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
