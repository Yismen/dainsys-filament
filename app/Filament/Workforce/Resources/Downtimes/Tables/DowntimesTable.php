<?php

namespace App\Filament\Workforce\Resources\Downtimes\Tables;

use App\Enums\DowntimeStatuses;
use App\Filament\Actions\AproveDowntimeAction;
use App\Models\Comment;
use App\Models\Downtime;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DowntimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('downtimeReason.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('aprover.name')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Downtime $record) => $record->status->value === 'Pending')
                    ->schema([
                        \Filament\Forms\Components\Textarea::make('comment')
                            ->label('Approval comment')
                            ->required(),
                    ])
                    ->action(function (Downtime $record, array $data) {
                        $record->aprove();

                        \App\Models\Comment::query()->forceCreate([
                            'text' => 'Approved: '.($data['comment'] ?? ''),
                            'commentable_id' => $record->id,
                            'commentable_type' => Downtime::class,
                        ]);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Downtime $record) => $record->status->value === 'Pending')
                    ->schema([
                        Textarea::make('comment')
                            ->label('Rejection comment')
                            ->required(),
                    ])
                    ->action(function (Downtime $record, array $data) {
                        $record->status = DowntimeStatuses::Rejected;
                        $record->aprover_id = null;
                        $record->saveQuietly();

                        if (method_exists($record, 'removeFromProduction')) {
                            $record->removeFromProduction();
                        }

                        \App\Models\Comment::query()->forceCreate([
                            'text' => 'Rejected: '.($data['comment'] ?? ''),
                            'commentable_id' => $record->id,
                            'commentable_type' => Downtime::class,
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    AproveDowntimeAction::make('aprove downtimes')
                        ->visible(true)
                        ->accessSelectedRecords()
                        ->schema([
                            Textarea::make('comment')
                                ->label('Approval comment')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function (Downtime $record) use ($data) {
                                if ($record->status === DowntimeStatuses::Pending) {
                                    $record->aprove();

                                    // Track approval comment
                                    Comment::query()->forceCreate([
                                        'text' => 'Approved: '.($data['comment'] ?? ''),
                                        'commentable_id' => $record->id,
                                        'commentable_type' => Downtime::class,
                                    ]);
                                }
                            });
                        }),
                    BulkAction::make('reject downtimes')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->accessSelectedRecords()
                        ->schema([
                            Textarea::make('comment')
                                ->label('Rejection comment')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function (Downtime $record) use ($data) {
                                if ($record->status === DowntimeStatuses::Pending) {
                                    $record->status = DowntimeStatuses::Rejected;
                                    $record->aprover_id = null;
                                    $record->saveQuietly();

                                    // Remove any production entries
                                    // (uses internal method on model)
                                    // Ensure method exists; if not, it's a no-op
                                    if (method_exists($record, 'removeFromProduction')) {
                                        $record->removeFromProduction();
                                    }

                                    // Track rejection comment
                                    Comment::query()->forceCreate([
                                        'text' => 'Rejected: '.($data['comment'] ?? ''),
                                        'commentable_id' => $record->id,
                                        'commentable_type' => Downtime::class,
                                    ]);
                                }
                            });
                        }),
                ]),
            ]);
    }
}
