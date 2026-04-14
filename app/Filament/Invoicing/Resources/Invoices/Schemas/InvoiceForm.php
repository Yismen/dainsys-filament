<?php

namespace App\Filament\Invoicing\Resources\Invoices\Schemas;

use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\InvoiceAgent;
use App\Models\Item;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required()
                    ->default(today())
                    ->maxDate(today()->addDays(5))
                    ->minDate(today()->subDays(25)),
                Select::make('project_id')
                    ->label(__('Project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('agent_id', null);
                        $set('campaign_id', null);
                    })
                    ->required(),
                Select::make('agent_id')
                    ->label(__('Agent'))
                    ->options(fn (Get $get) => ModelListService::make(
                        InvoiceAgent::query()
                            ->when(
                                filled($get('project_id')),
                                fn ($query) => $query->where('project_id', $get('project_id')),
                            )
                    ))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('campaign_id', null);
                    })
                    ->disabled(fn (Get $get): bool => blank($get('project_id')))
                    ->placeholder(__('Unassigned')),
                Select::make('campaign_id')
                    ->label(__('Campaign'))
                    ->options(fn (Get $get) => ModelListService::make(
                        Campaign::query()
                            ->when(
                                filled($get('agent_id')),
                                fn ($query) => $query->where('invoice_agent_id', $get('agent_id')),
                                fn ($query) => $query->whereRaw('1 = 0'),
                            )
                    ))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('items', []);
                    })
                    ->disabled(fn (Get $get): bool => blank($get('agent_id')))
                    ->placeholder(__('Unassigned')),
                Repeater::make('items')
                    ->label(__('Items'))
                    ->defaultItems(1)
                    ->required()
                    ->reorderable()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('item_id')
                            ->label(__('Item'))
                            ->options(function (Get $get): array {
                                $campaignId = $get('../../campaign_id');

                                if (! $campaignId) {
                                    return [];
                                }

                                return Item::query()
                                    ->where('campaign_id', $campaignId)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->live()
                            ->required()
                            ->columnSpan(5)
                            ->disabled(fn (Get $get): bool => blank($get('../../campaign_id')))
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (! $state) {
                                    $set('quantity', 1);
                                    $set('price', null);

                                    return;
                                }

                                $item = Item::query()->find($state);

                                if (! $item) {
                                    $set('quantity', 1);
                                    $set('price', null);

                                    return;
                                }

                                $set('quantity', 1);
                                $set('price', (string) $item->price);
                            }),
                        TextInput::make('quantity')
                            ->label(__('Quantity'))
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->live()
                            ->required()
                            ->columnSpan(2),
                        TextEntry::make('unit_price')
                            ->label(__('Unit price'))
                            ->state(function (Get $get): string {
                                $price = (float) ($get('price') ?? 0);

                                return number_format($price, 2, '.', '');
                            })
                            ->columnSpan(2),
                        TextEntry::make('line_subtotal')
                            ->label(__('Line subtotal'))
                            ->state(function (Get $get): string {
                                $price = (float) ($get('price') ?? 0);
                                $quantity = (int) ($get('quantity') ?? 1);

                                return number_format($price * max($quantity, 1), 2, '.', '');
                            })
                            ->columnSpan(3),
                        Hidden::make('price')
                            ->dehydrated()
                            ->required(),
                    ]),
                Grid::make(4)
                    ->schema([
                        TextEntry::make('general_client')
                            ->label(__('Client'))
                            ->state(function (Get $get, ?Invoice $record): string {
                                $projectId = $get('project_id') ?: $record?->project_id;

                                if (! $projectId) {
                                    return '-';
                                }

                                return Project::query()->find($projectId)?->client?->name ?? '-';
                            }),
                        TextEntry::make('general_invoice_number')
                            ->label(__('Invoice number'))
                            ->state(fn (?Invoice $record): string => $record?->number ?? __('Auto-generated on save')),
                        TextEntry::make('general_subtotal')
                            ->label(__('Subtotal'))
                            ->state(function (Get $get, ?Invoice $record): string {
                                $items = $get('items');

                                if (! is_array($items)) {
                                    return number_format((float) ($record?->subtotal_amount ?? 0), 2, '.', '');
                                }

                                $subtotal = 0.0;
                                foreach ($items as $item) {
                                    if (! is_array($item) || ! isset($item['price'])) {
                                        continue;
                                    }

                                    $quantity = max((int) ($item['quantity'] ?? 1), 1);
                                    $subtotal += (float) $item['price'] * $quantity;
                                }

                                return number_format($subtotal, 2, '.', '');
                            }),
                        TextEntry::make('general_total')
                            ->label(__('Total'))
                            ->state(function (Get $get, ?Invoice $record): string {
                                $items = $get('items');
                                $subtotal = 0.0;

                                if (is_array($items)) {
                                    foreach ($items as $item) {
                                        if (! is_array($item) || ! isset($item['price'])) {
                                            continue;
                                        }

                                        $quantity = max((int) ($item['quantity'] ?? 1), 1);
                                        $subtotal += (float) $item['price'] * $quantity;
                                    }
                                } else {
                                    $subtotal = (float) ($record?->subtotal_amount ?? 0);
                                }

                                return number_format($subtotal, 2, '.', '');
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
