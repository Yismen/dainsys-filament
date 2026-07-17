<?php

namespace App\Filament\Workforce\Widgets;

use App\Enums\DowntimeStatuses;
use App\Models\Comment;
use App\Models\Downtime;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PendingDowntimesTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pending downtime approvals';

    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('date')
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
                    ->label(__('filament.reason'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->label(__('filament.minutes'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label(__('filament.requested_by'))
                    ->wrap()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.requested_at'))
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label(__('filament.approve'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Downtime $record): bool => $record->status === DowntimeStatuses::Pending)
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
                        ->visible(fn (Downtime $record): bool => $record->status === DowntimeStatuses::Pending)
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
                    BulkAction::make('approve_selected')
                        ->label(__('filament.approve_selected'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->accessSelectedRecords()
                        ->schema([
                            Textarea::make('comment')
                                ->label(__('filament.approval_comment'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records
                                ->filter(fn (Downtime $record) => $record->status === DowntimeStatuses::Pending)
                                ->each(function (Downtime $record) use ($data): void {
                                    $record->aprove();

                                    Comment::query()->forceCreate([
                                        'text' => 'Approved: '.($data['comment'] ?? ''),
                                        'commentable_id' => $record->id,
                                        'commentable_type' => Downtime::class,
                                    ]);
                                });
                        }),
                    BulkAction::make('reject_selected')
                        ->label(__('filament.reject_selected'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->accessSelectedRecords()
                        ->schema([
                            Textarea::make('comment')
                                ->label(__('filament.rejection_comment'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records
                                ->filter(fn (Downtime $record) => $record->status === DowntimeStatuses::Pending)
                                ->each(function (Downtime $record) use ($data): void {
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
                                });
                        }),
                ]),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Downtime::query()
            ->where('status', DowntimeStatuses::Pending)
            ->with([
                'employee',
                'campaign',
                'downtimeReason',
                'requester',
            ]);
    }
}
