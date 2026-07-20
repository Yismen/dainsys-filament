<?php

namespace App\Filament\Invoicing\Pages;

use App\Filament\Invoicing\Widgets\IncomeByMonthChart;
use App\Filament\Invoicing\Widgets\IncomeByProjectChart;
use App\Filament\Invoicing\Widgets\InvoiceSummaryStats;
use App\Filament\Invoicing\Widgets\OutstandingInvoicesTable;
use App\Models\Client;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class InvoicingDashboardPage extends Dashboard
{
    use HasFiltersForm;

    protected static string $routePath = '/';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            InvoiceSummaryStats::class,
            IncomeByProjectChart::class,
            IncomeByMonthChart::class,
            OutstandingInvoicesTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }

    public function persistsFiltersInSession(): bool
    {
        return false;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'md' => 1,
                'xl' => 1,
                '2xl' => 1,
            ])
            ->components([
                Section::make()
                    ->heading(__('filament.dashboard_filters'))
                    ->description(__('filament.dashboard_filters_description'))
                    ->columns([
                        'md' => 2,
                        'xl' => 4,
                    ])
                    ->schema([
                        DatePicker::make('start_date')
                            ->label(__('filament.start_date'))
                            ->default(now()->subMonths(6)->startOfMonth())
                            ->placeholder(__('filament.from'))
                            ->live(),
                        DatePicker::make('end_date')
                            ->label(__('filament.end_date'))
                            ->default(now()->endOfMonth())
                            ->placeholder(__('filament.to'))
                            ->live(),
                        Select::make('client_id')
                            ->label(__('filament.client'))
                            ->searchable()
                            ->placeholder(__('filament.all_clients'))
                            ->live()
                            ->options(ModelListService::make(Client::query())),
                        Select::make('project_id')
                            ->label(__('filament.project'))
                            ->searchable()
                            ->placeholder(__('filament.all_projects'))
                            ->live()
                            ->options(fn (Get $get): array => ModelListService::make(
                                Project::query()
                                    ->when(
                                        filled($get('client_id')),
                                        fn ($query) => $query->where('client_id', $get('client_id')),
                                    )
                            )),
                    ]),
            ]);
    }
}
