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
                    ->label(__('Invoice'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project.client.name')
                    ->label(__('Client'))
                    ->wrap()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->wrap()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->wrap()
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label(__('Due date'))
                    ->wrap()
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->wrap()
                    ->badge()
                    ->formatStateUsing(fn (InvoiceStatuses $state): string => $state->getLabel())
                    ->color(fn (InvoiceStatuses $state): string => $state->getColor()),
                TextColumn::make('total_amount')
                    ->label(__('Invoiced'))
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::currency($state)),
                TextColumn::make('total_paid')
                    ->label(__('Paid'))
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::currency($state)),
                TextColumn::make('balance_pending')
                    ->label(__('Pending'))
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::currency($state)),
            ])
            ->defaultSort('due_date')
            ->filters([
                SelectFilter::make('client_id')
                    ->label(__('Client'))
                    ->options(ModelListService::make(Client::query()))
                    ->searchable()
                    ->placeholder(__('All clients'))
                    ->query(function (Builder $query, $value): Builder {
                        return $query->when(
                            filled($value),
                            fn (Builder $builder): Builder => $builder->whereHas('project', fn (Builder $projectQuery): Builder => $projectQuery->where('client_id', $value)),
                        );
                    }),
                SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->placeholder(__('All projects')),
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(collect(InvoiceStatuses::cases())->mapWithKeys(fn (InvoiceStatuses $status): array => [
                        $status->value => $status->getLabel(),
                    ])->toArray())
                    ->placeholder(__('All statuses')),
                Filter::make('due_date_range')
                    ->label(__('Due date range'))
                    ->schema([
                        DatePicker::make('due_from')
                            ->label(__('Due from')),
                        DatePicker::make('due_until')
                            ->label(__('Due until')),
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
                    ->label(__('View'))
                    ->modalHeading(fn (Invoice $record): string => __('Invoice :number', ['number' => $record->number]))
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
