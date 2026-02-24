<?php

namespace App\Filament\Workforce\Resources\Downtimes\Tables;

use App\Enums\DowntimeStatuses;
use App\Actions\Filament\AproveDowntimeAction;
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
                    ->label("ID")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->relationship('employee', 'full_name')
                    ->options(ModelListService::make(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('campaign_id')
                    ->label('Campaign')
                    ->relationship('campaign', 'name')
                    ->searchable()
                    ->preload()
                    ->options(ModelListService::make(Campaign::query())),
                SelectFilter::make('requester_id')
                    ->label('Requester')
                    ->relationship('requester', 'name')
                    ->options(ModelListService::make(User::query()))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('aprover_id')
                    ->label('Approver')
                    ->searchable()
                    ->relationship('aprover', 'name')
                    ->options(ModelListService::make(User::query()))
                    ->preload(),
                SelectFilter::make('status')
                    ->options(DowntimeStatuses::class)
                    ->default(DowntimeStatuses::Pending->value),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Downtime $record) => $record->status->value === 'Pending')
                        ->schema([
                            Textarea::make('comment')
                                ->label('Approval comment')
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
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Downtime $record) => $record->status->value === 'Pending')
                        ->schema([
                            Textarea::make('comment')
                                ->label('Rejection comment')
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
                                ->label('Approval comment')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function (Downtime $record) use ($data): void {
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
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function (Downtime $record) use ($data): void {
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
