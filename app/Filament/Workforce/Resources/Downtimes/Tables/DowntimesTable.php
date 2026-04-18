<?php

namespace App\Filament\Workforce\Resources\Downtimes\Tables;

use App\Actions\Filament\Downtime\AproveDowntimeAction;
use App\Enums\DowntimeStatuses;
use App\Models\Campaign;
use App\Models\Comment;
use App\Models\Downtime;
use App\Models\Employee;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DowntimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::FiveExtraLarge)
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->label(__('filament.campaign'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('downtimeReason.name')
                    ->label(__('filament.downtime_reason'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->label(__('filament.total_time'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label(__('filament.requester'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('aprover.name')
                    ->label(__('filament.approver'))
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
                    ->options(ModelListService::make(model: Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label(__('filament.campaign'))
                    ->searchable()
                    ->options(ModelListService::make(Campaign::query())),
                SelectFilter::make('requester_id')
                    ->label(__('filament.requester'))
                    ->options(ModelListService::make(User::query()))
                    ->searchable(),
                SelectFilter::make('aprover_id')
                    ->label(__('filament.approver'))
                    ->searchable()
                    ->options(ModelListService::make(User::query())),
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options(DowntimeStatuses::class)
                    ->default(DowntimeStatuses::Pending->value),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('approve')
                        ->label(__('filament.approve'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Downtime $record) => $record->status->value === 'Pending')
                        ->schema([
                            Textarea::make('comment')
                                ->label(__('filament.approval_comment'))
                                ->required(),
                        ])
                        ->action(function (Downtime $record, array $data): void {
                            $record->aprove();

                            Comment::query()->forceCreate([
                                'text' => 'Approved: '.($data['comment'] ?? ''),
                                'commentable_id' => $record->id,
                                'commentable_type' => Downtime::class,
                            ]);
                        }),
                    Action::make('reject')
                        ->label(__('filament.reject'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Downtime $record) => $record->status->value === 'Pending')
                        ->schema([
                            Textarea::make('comment')
                                ->label(__('filament.rejection_comment'))
                                ->required(),
                        ])
                        ->action(function (Downtime $record, array $data): void {
                            $record->status = DowntimeStatuses::Rejected;
                            $record->aprover_id = null;
                            $record->saveQuietly();

                            if (method_exists($record, 'removeFromProduction')) {
                                $record->removeFromProduction();
                            }

                            Comment::query()->forceCreate([
                                'text' => 'Rejected: '.($data['comment'] ?? ''),
                                'commentable_id' => $record->id,
                                'commentable_type' => Downtime::class,
                            ]);
                        }),
                ])->iconButton(),
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
                                ->label(__('filament.approval_comment'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function (Downtime $record) use ($data): void {
                                if ($record->status === DowntimeStatuses::Pending) {
                                    $record->aprove();

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
                                ->label(__('filament.rejection_comment'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function (Downtime $record) use ($data): void {
                                if ($record->status === DowntimeStatuses::Pending) {
                                    $record->status = DowntimeStatuses::Rejected;
                                    $record->aprover_id = null;
                                    $record->saveQuietly();

                                    if (method_exists($record, 'removeFromProduction')) {
                                        $record->removeFromProduction();
                                    }

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
