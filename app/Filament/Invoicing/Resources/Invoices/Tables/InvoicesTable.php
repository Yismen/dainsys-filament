<?php

namespace App\Filament\Invoicing\Resources\Invoices\Tables;

use App\Actions\Filament\Invoice\PayInvoiceAction;
use App\Enums\InvoiceStatuses;
use App\Models\Campaign;
use App\Models\InvoiceAgent;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder as DabasaseQueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Illuminate\View\View;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Stack::make([
                    Grid::make()
                        ->schema([
                            TextColumn::make('number')
                                ->copyable()
                                ->sortable()
                                ->searchable()
                                ->formatStateUsing(fn (string $state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Invoice Number'),
                                        'value' => $state,
                                    ]
                                )),
                        ]),
                    Grid::make([
                        'sm' => 3,
                    ])
                        ->schema([
                            TextColumn::make('date')
                                ->date()
                                ->sortable()
                                ->formatStateUsing(fn (string $state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Invoice Date'),
                                        'value' => Carbon::parse($state)->format('M d, Y'),
                                    ])),
                            TextColumn::make('due_date')
                                ->date()
                                ->sortable()
                                ->formatStateUsing(fn ($state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Due At'),
                                        'value' => $state->format('M d, Y'),
                                    ])),
                            TextColumn::make('status')
                                ->badge()
                                ->color(fn ($state) => $state->getColor())
                                ->formatStateUsing(fn (InvoiceStatuses $state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Invoice Status'),
                                        'value' => $state->getLabel(),
                                    ])),
                        ]),
                    Grid::make([
                        'sm' => 3,
                    ])
                        ->schema([
                            TextColumn::make('total_amount')
                                ->numeric()
                                ->sortable()
                                ->summarize(Summarizer::make()->using(fn (DabasaseQueryBuilder $query) => Number::currency($query->sum('total_amount') / 100)))
                                ->formatStateUsing(fn ($state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Total Amount'),
                                        'value' => Number::currency($state),
                                    ])),
                            TextColumn::make('total_paid')
                                ->numeric()
                                ->sortable()
                                ->summarize(Summarizer::make()->using(fn (DabasaseQueryBuilder $query) => Number::currency($query->sum('total_paid') / 100)))
                                ->formatStateUsing(fn ($state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Amount Paid'),
                                        'value' => Number::currency($state),
                                    ])),
                            TextColumn::make('balance_pending')
                                ->label('Balance')
                                ->numeric()
                                ->color(fn (float $state) => $state == 0 ? Color::Green : Color::Red)
                                ->summarize(Summarizer::make()->using(fn (DabasaseQueryBuilder $query) => Number::currency($query->sum('balance_pending') / 100)))
                                ->formatStateUsing(fn ($state): View => view(
                                    'filament.invoices.table.state', [
                                        'label' => __('Balance'),
                                        'value' => Number::currency($state),
                                    ])),
                        ]),
                    Panel::make([
                        Grid::make([
                            'sm' => 2,
                            'xl' => 4,
                        ])
                            ->columns(2)
                            ->schema([
                                TextColumn::make('project.client.name')
                                    ->label('Client')
                                    ->searchable()
                                    ->sortable()
                                    ->formatStateUsing(fn ($state): View => view(
                                        'filament.invoices.table.state', [
                                            'label' => __('Client'),
                                            'value' => $state,
                                        ])),
                                TextColumn::make('project.name')
                                    ->label('Project')
                                    ->searchable()
                                    ->sortable()
                                    ->formatStateUsing(fn ($state): View => view(
                                        'filament.invoices.table.state', [
                                            'label' => __('Project'),
                                            'value' => $state,
                                        ])),
                                TextColumn::make('agent.name')
                                    ->label('Agent')
                                    ->searchable()
                                    ->sortable()
                                    ->formatStateUsing(fn ($state): View => view(
                                        'filament.invoices.table.state', [
                                            'label' => __('Agent'),
                                            'value' => $state,
                                        ])),
                                TextColumn::make('campaign.name')
                                    ->label('Campaign')
                                    ->searchable()
                                    ->sortable()
                                    ->formatStateUsing(fn ($state): View => view(
                                        'filament.invoices.table.state', [
                                            'label' => __('Campaign'),
                                            'value' => $state,
                                        ])),
                            ]),
                    ])
                        ->collapsible()
                        ->collapsed(),
                ])
                    ->space(3),
            ])
            // ->filters(InvoiceTableFilters::make())
            ->deferFilters()
            ->filtersFormWidth('lg')
            ->recordActionsAlignment(Alignment::End->value)
            ->recordActions([
                // DownloadInvoiceAction::make(),
                ViewAction::make()
                    ->openUrlInNewTab()
                    ->button(),
                EditAction::make()
                    ->visible(fn ($record) => $record->status !== InvoiceStatuses::Paid)
                    ->button()
                    ->modalWidth('7xl')
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(),
                // PayInvoiceAction::make(),
                // CancelInvoiceAction::make(),
                // RestoreCancelledInvoiceAction::make(),
            ])
            ->recordUrl(null)
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
                // ExportBulkAction::make()
                //     ->label('Export Selected')
                //     ->size('xs')
                //     ->icon('heroicon-s-arrow-down-tray')
                //     ->color(Color::Amber)
                //     ->deselectRecordsAfterCompletion()
                //     ->exporter(InvoiceExporter::class),
                // PayBulkInvoicesAction::make(),
                // DownloadBulInvoicesAction::make(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(collect(InvoiceStatuses::cases())->mapWithKeys(fn (InvoiceStatuses $status): array => [
                        $status->value => str($status->value)->replace('_', ' ')->title()->toString(),
                    ])->toArray()),
                SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
                SelectFilter::make('agent_id')
                    ->label(__('Agent'))
                    ->options(ModelListService::make(InvoiceAgent::query()))
                    ->searchable(),
                SelectFilter::make('campaign_id')
                    ->label(__('Campaign'))
                    ->options(ModelListService::make(Campaign::query()))
                    ->searchable(),
                TrashedFilter::make()
                    ->label(__('Trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create Invoice'))
                    ->modalHeading(__('Create Invoice'))
                    ->modalWidth('7xl')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByClickingAway(false),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('View'))
                    ->modalHeading(__('View Invoice')),
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Invoice'))
                    ->modalWidth('7xl')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->closeModalByClickingAway(false),
                PayInvoiceAction::make(),
            ])
            ->recordActionsAlignment(Alignment::End->value)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('Delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('Force delete')),
                    RestoreBulkAction::make()
                        ->label(__('Restore')),
                ])->label(__('Bulk actions')),
            ]);
    }
}
