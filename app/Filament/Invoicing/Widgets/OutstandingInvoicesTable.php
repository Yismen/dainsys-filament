<?php

namespace App\Filament\Invoicing\Widgets;

use App\Enums\InvoiceStatuses;
use App\Filament\Invoicing\Resources\Invoices\Schemas\InvoiceInfolist;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class OutstandingInvoicesTable extends TableWidget
{
    protected static ?string $heading = 'Outstanding invoices';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('number')
                    ->label(__('filament.invoice'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project.client.name')
                    ->label(__('filament.client'))
                    ->wrap()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->wrap()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->wrap()
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label(__('filament.due_date'))
                    ->wrap()
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->wrap()
                    ->badge()
                    ->formatStateUsing(fn (InvoiceStatuses $state): string => $state->getLabel())
                    ->color(fn (InvoiceStatuses $state): string => $state->getColor()),
                TextColumn::make('total_amount')
                    ->label(__('filament.invoiced'))
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::currency($state)),
                TextColumn::make('total_paid')
                    ->label(__('filament.paid'))
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::currency($state)),
                TextColumn::make('balance_pending')
                    ->label(__('filament.pending'))
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::currency($state)),
            ])
            ->defaultSort('due_date')
            ->filters([
                SelectFilter::make('client_id')
                    ->label(__('filament.client'))
                    ->options(ModelListService::make(Client::query()))
                    ->searchable()
                    ->placeholder(__('filament.all_clients'))
                    ->query(function (Builder $query, $value): Builder {
                        return $query->when(
                            filled($value),
                            fn (Builder $builder): Builder => $builder->whereHas('project', fn (Builder $projectQuery): Builder => $projectQuery->where('client_id', $value)),
                        );
                    }),
                SelectFilter::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->placeholder(__('filament.all_projects')),
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options(collect(InvoiceStatuses::cases())->mapWithKeys(fn (InvoiceStatuses $status): array => [
                        $status->value => $status->getLabel(),
                    ])->toArray())
                    ->placeholder(__('filament.all_statuses')),
                Filter::make('due_date_range')
                    ->label(__('filament.due_date_range'))
                    ->schema([
                        DatePicker::make('due_from')
                            ->label(__('filament.due_from')),
                        DatePicker::make('due_until')
                            ->label(__('filament.due_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['due_from'] ?? null),
                                fn (Builder $builder): Builder => $builder->whereDate('due_date', '>=', $data['due_from']),
                            )
                            ->when(
                                filled($data['due_until'] ?? null),
                                fn (Builder $builder): Builder => $builder->whereDate('due_date', '<=', $data['due_until']),
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->paginated([10, 25, 50])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->modalHeading(fn (Invoice $record): string => __('filament.invoice_number_display', ['number' => $record->number]))
                    ->schema(fn (Schema $schema): Schema => InvoiceInfolist::configure($schema))
                    ->modalWidth('3xl'),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return Invoice::query()
            ->where('balance_pending', '>', 0)
            ->with(['project.client']);
    }
}
